<?php
session_start();
include('includes/config.php'); // PDO connection in $dbh
error_reporting(0);

// 1) Ensure user is logged in
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: logout.php");
    exit;
}
$userId = $_SESSION['usrid'];

// (A) Remove cart item if "?del=ID"
if (isset($_GET['del']) && !empty($_GET['del'])) {
    $delId = intval($_GET['del']);
    try {
        $delSql = "DELETE FROM cart WHERE id = :cartid AND userid = :uid";
        $delStmt = $dbh->prepare($delSql);
        $delStmt->bindParam(':cartid', $delId, PDO::PARAM_INT);
        $delStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $delStmt->execute();
        echo "<script>alert('Item removed from cart');</script>";
        echo "<script>window.location.href='checkout.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error removing item: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// (B) Insert new address if form is submitted
if (isset($_POST['add_address'])) {
    $billingAddress   = $_POST['billingAddress']   ?? '';
    $billingCity      = $_POST['billingCity']      ?? '';
    $billingState     = $_POST['billingState']     ?? '';
    $billingCountry   = $_POST['billingCountry']   ?? '';
    $billingPincode   = $_POST['billingPincode']   ?? '';

    $shippingAddress  = $_POST['shippingAddress']  ?? '';
    $shippingCity     = $_POST['shippingCity']     ?? '';
    $shippingState    = $_POST['shippingState']    ?? '';
    $shippingCountry  = $_POST['shippingCountry']  ?? '';
    $shippingPincode  = $_POST['shippingPincode']  ?? '';

    try {
        $addrSql = "INSERT INTO addresses
        (
          userId,
          billingAddress, billingCity, billingState, billingCountry, billingPincode,
          shippingAddress, shippingCity, shippingState, shippingCountry, shippingPincode
        )
        VALUES
        (
          :uid,
          :bAddr, :bCity, :bState, :bCountry, :bPin,
          :sAddr, :sCity, :sState, :sCountry, :sPin
        )";
        $addrStmt = $dbh->prepare($addrSql);

        $addrStmt->bindParam(':uid',     $userId,            PDO::PARAM_INT);
        $addrStmt->bindParam(':bAddr',   $billingAddress,    PDO::PARAM_STR);
        $addrStmt->bindParam(':bCity',   $billingCity,       PDO::PARAM_STR);
        $addrStmt->bindParam(':bState',  $billingState,      PDO::PARAM_STR);
        $addrStmt->bindParam(':bCountry',$billingCountry,    PDO::PARAM_STR);
        $addrStmt->bindParam(':bPin',    $billingPincode,    PDO::PARAM_STR);

        $addrStmt->bindParam(':sAddr',   $shippingAddress,   PDO::PARAM_STR);
        $addrStmt->bindParam(':sCity',   $shippingCity,      PDO::PARAM_STR);
        $addrStmt->bindParam(':sState',  $shippingState,     PDO::PARAM_STR);
        $addrStmt->bindParam(':sCountry',$shippingCountry,   PDO::PARAM_STR);
        $addrStmt->bindParam(':sPin',    $shippingPincode,   PDO::PARAM_STR);

        $addrStmt->execute();

        echo "<script>alert('Address added successfully');</script>";
        echo "<script>window.location.href='checkout.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error adding address: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// (C) Proceed to Payment
if (isset($_POST['proceed_payment'])) {
    $selectedAddressId = $_POST['addressId'] ?? 0;
    $cartTotal         = $_POST['cartTotal']  ?? 0;

    $_SESSION['checkoutAddressId'] = $selectedAddressId;
    $_SESSION['checkoutTotal']     = $cartTotal;

    echo "<script>window.location.href='payment.php';</script>";
    exit;
}

// (D) Fetch cart items
$cartItems = [];
$cartTotal = 0;
try {
    $cartSql = "SELECT c.id AS cartid, c.productid, c.quantity,
                       p.productName, p.productPrice, p.productImage1
                FROM cart c
                LEFT JOIN products p ON c.productid = p.id
                WHERE c.userid = :uid";
    $cartStmt = $dbh->prepare($cartSql);
    $cartStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
    $cartStmt->execute();
    if ($cartStmt->rowCount() > 0) {
        $cartItems = $cartStmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($cartItems as $item) {
            $cartTotal += ($item->productPrice * $item->quantity);
        }
    }
} catch (Exception $e) {
    $cartItems = [];
}

// (E) Fetch addresses
$userAddresses = [];
try {
    $addrSql = "SELECT * FROM addresses WHERE userId = :uid ORDER BY id DESC";
    $addrStmt = $dbh->prepare($addrSql);
    $addrStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
    $addrStmt->execute();
    $userAddresses = ($addrStmt->rowCount() > 0) ? $addrStmt->fetchAll(PDO::FETCH_OBJ) : [];
} catch (Exception $e) {
    $userAddresses = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <!-- Project CSS (Bootstrap, etc.) -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/nivo-slider.css">
    <link rel="stylesheet" href="css/animate-text.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="css/color/skin-default.css" rel="stylesheet">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <style>
    /* Simple styling for toggling form */
    #addAddressForm {
        display: none; /* hidden by default */
        margin-top: 20px;
        padding: 15px;
        background: #f8f8f8;
        border: 1px solid #ddd;
    }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcumb -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Checkout</li>
            </ol>
        </div>
    </div>

    <div class="container" style="margin-top:50px; margin-bottom:50px;">
        <!-- CART SECTION -->
        <h2>My Cart</h2>
        <?php if (count($cartItems) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartItems as $item):
                        $itemTotal = $item->productPrice * $item->quantity;
                    ?>
                        <tr>
                            <td>
                                <?php
                                $imgPath = (!empty($item->productImage1))
                                    ? "admin/productimages/" . $item->productImage1
                                    : "images/placeholder.png";
                                ?>
                                <img src="<?php echo htmlentities($imgPath); ?>"
                                     alt="Product"
                                     style="width:60px; height:auto;">
                            </td>
                            <td><?php echo htmlentities($item->productName); ?></td>
                            <td>$<?php echo htmlentities($item->productPrice); ?></td>
                            <td><?php echo htmlentities($item->quantity); ?></td>
                            <td>$<?php echo htmlentities($itemTotal); ?></td>
                            <td>
                                <a href="checkout.php?del=<?php echo $item->cartid; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Remove this item?');">
                                   Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td colspan="4" align="right"><strong>Cart Total:</strong></td>
                            <td colspan="2"><strong>$<?php echo $cartTotal; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
        <?php endif; ?>

        <hr>

        <!-- SAVED ADDRESSES -->
        <h2>Saved Addresses</h2>
        <?php if (count($userAddresses) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Billing Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Pincode</th>
                            <th>Shipping Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Pincode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userAddresses as $addr): ?>
                        <tr>
                            <td><?php echo htmlentities($addr->id); ?></td>
                            <td><?php echo nl2br(htmlentities($addr->billingAddress)); ?></td>
                            <td><?php echo htmlentities($addr->billingCity); ?></td>
                            <td><?php echo htmlentities($addr->billingState); ?></td>
                            <td><?php echo htmlentities($addr->billingCountry); ?></td>
                            <td><?php echo htmlentities($addr->billingPincode); ?></td>
                            <td><?php echo nl2br(htmlentities($addr->shippingAddress)); ?></td>
                            <td><?php echo htmlentities($addr->shippingCity); ?></td>
                            <td><?php echo htmlentities($addr->shippingState); ?></td>
                            <td><?php echo htmlentities($addr->shippingCountry); ?></td>
                            <td><?php echo htmlentities($addr->shippingPincode); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No saved addresses yet.</p>
        <?php endif; ?>

        <!-- Button to show the "Add New Address" form -->
        <button class="btn btn-info" id="showAddFormBtn" style="margin-top:15px;">
            Add New Address
        </button>

        <!-- ADD NEW ADDRESS FORM (hidden by default) -->
        <div id="addAddressForm">
            <h3 style="margin-top:0;">Add New Address</h3>
            <form method="post">
                <div class="row">
                    <!-- Left Column: Billing -->
                    <div class="col-md-6">
                        <h4>Billing</h4>
                        <div class="form-group">
                            <label for="billingAddress">Address</label>
                            <textarea name="billingAddress" id="billingAddress" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="billingCity">City</label>
                            <input type="text" name="billingCity" id="billingCity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingState">State</label>
                            <input type="text" name="billingState" id="billingState" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingCountry">Country</label>
                            <input type="text" name="billingCountry" id="billingCountry" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingPincode">Pincode</label>
                            <input type="text" name="billingPincode" id="billingPincode" class="form-control" required>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" id="sameAsBilling" class="form-check-input">
                            <label for="sameAsBilling" class="form-check-label">Shipping same as Billing</label>
                        </div>
                    </div>

                    <!-- Right Column: Shipping -->
                    <div class="col-md-6">
                        <h4>Shipping</h4>
                        <div class="form-group">
                            <label for="shippingAddress">Address</label>
                            <textarea name="shippingAddress" id="shippingAddress" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="shippingCity">City</label>
                            <input type="text" name="shippingCity" id="shippingCity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingState">State</label>
                            <input type="text" name="shippingState" id="shippingState" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingCountry">Country</label>
                            <input type="text" name="shippingCountry" id="shippingCountry" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingPincode">Pincode</label>
                            <input type="text" name="shippingPincode" id="shippingPincode" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="add_address" class="btn btn-primary">Save Address</button>
            </form>
        </div>

        <hr>

        <!-- PROCEED TO PAYMENT -->
        <h2>Proceed to Payment</h2>
        <?php if ($cartTotal > 0 && count($userAddresses) > 0): ?>
            <form method="post">
                <div class="form-group">
                    <label for="addressId">Select Address</label>
                    <select name="addressId" id="addressId" class="form-control" required>
                        <option value="">--Choose an address--</option>
                        <?php foreach ($userAddresses as $addr): ?>
                            <option value="<?php echo $addr->id; ?>">
                                <?php
                                echo "Billing: {$addr->billingCity} - {$addr->billingPincode}, "
                                   . "Shipping: {$addr->shippingCity} - {$addr->shippingPincode}";
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="cartTotal" value="<?php echo $cartTotal; ?>">
                <button type="submit" name="proceed_payment" class="btn btn-success">
                    Proceed to Payment
                </button>
            </form>
        <?php else: ?>
            <p>Either your cart is empty or you have no addresses. Cannot proceed to payment.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.meanmenu.js"></script>
    <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
    <script src="js/nivo-slider/nivo-active.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <script>
    // Show/hide the "Add New Address" form
    document.addEventListener('DOMContentLoaded', function() {
      var addForm = document.getElementById('addAddressForm');
      var showBtn = document.getElementById('showAddFormBtn');
      showBtn.addEventListener('click', function() {
        if (addForm.style.display === 'none') {
          addForm.style.display = 'block';
          showBtn.textContent = 'Hide Address Form';
        } else {
          addForm.style.display = 'none';
          showBtn.textContent = 'Add New Address';
        }
      });

      // Copy billing -> shipping
      var sameAsBilling = document.getElementById('sameAsBilling');
      sameAsBilling.addEventListener('change', function() {
          if (this.checked) {
              document.getElementById('shippingAddress').value  = document.getElementById('billingAddress').value;
              document.getElementById('shippingCity').value     = document.getElementById('billingCity').value;
              document.getElementById('shippingState').value    = document.getElementById('billingState').value;
              document.getElementById('shippingCountry').value  = document.getElementById('billingCountry').value;
              document.getElementById('shippingPincode').value  = document.getElementById('billingPincode').value;
          } else {
              document.getElementById('shippingAddress').value  = '';
              document.getElementById('shippingCity').value     = '';
              document.getElementById('shippingState').value    = '';
              document.getElementById('shippingCountry').value  = '';
              document.getElementById('shippingPincode').value  = '';
          }
      });
    });
    </script>
</body>
</html>
