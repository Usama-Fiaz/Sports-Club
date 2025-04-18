<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

// 1) Ensure user is logged in & session data is present
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid']) ||
    !isset($_SESSION['checkoutAddressId']) || !isset($_SESSION['checkoutTotal'])) {
    header("Location: checkout.php");
    exit;
}

$userId    = $_SESSION['usrid'];
$addressId = intval($_SESSION['checkoutAddressId']);
$cartTotal = floatval($_SESSION['checkoutTotal']); // Convert session total to float

// If user submitted the payment form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    // 2) Gather payment details
    $txntype   = $_POST['txntype'] ?? 'COD';   // e.g. 'COD' or 'Online'
    $txnnumber = trim($_POST['txnnumber'] ?? '');

    // Generate a random alphanumeric order number
    $stringOrderNumber = 'ORD' . strtoupper(uniqid());

    try {
        // 3) Begin transaction
        $dbh->beginTransaction();

        // 4) Insert into orders table
        // columns: (id, userId, addressId, totalAmount, txntype, txnnumber, orderNumber, orderDate, orderStatus)
        $orderSql = "INSERT INTO orders
            (userId, addressId, totalAmount, txntype, txnnumber, orderNumber, orderDate, orderStatus)
            VALUES
            (:userId, :addressId, :totalAmount, :txntype, :txnnumber, :strOrderNum, NOW(), 'Pending')";
        $orderStmt = $dbh->prepare($orderSql);
        $orderStmt->bindParam(':userId',       $userId,             PDO::PARAM_INT);
        $orderStmt->bindParam(':addressId',    $addressId,          PDO::PARAM_INT);
        $orderStmt->bindParam(':totalAmount',  $cartTotal,          PDO::PARAM_STR);
        $orderStmt->bindParam(':txntype',      $txntype,            PDO::PARAM_STR);
        $orderStmt->bindParam(':txnnumber',    $txnnumber,          PDO::PARAM_STR);
        $orderStmt->bindParam(':strOrderNum',  $stringOrderNumber,  PDO::PARAM_STR);
        $orderStmt->execute();

        // 5) Get the newly inserted order's ID (integer PK from orders.id)
        $orderId = $dbh->lastInsertId();

        // 6) Fetch cart items for the user
        // cart => (id, userId, productId, quantity)
        // products => must have productPrice if you want to track it
        $cartSql = "SELECT c.productid, c.quantity, p.productPrice
                    FROM cart c
                    LEFT JOIN products p ON c.productid = p.id
                    WHERE c.userid = :uid";
        $cartStmt = $dbh->prepare($cartSql);
        $cartStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $cartStmt->execute();
        $cartItems = $cartStmt->fetchAll(PDO::FETCH_OBJ);

        // 7) Insert each item into order_details
        // columns: (id, orderNumber, userId, productId, quantity, orderDate, orderStatus)
        if ($cartItems) {
            $detailsSql = "INSERT INTO order_details
                (orderNumber, userId, productId, quantity, orderDate, orderStatus)
                VALUES
                (:orderNumber, :userId, :productId, :quantity, NOW(), 'Pending')";
            $detailsStmt = $dbh->prepare($detailsSql);

            foreach ($cartItems as $item) {
                // Instead of $orderId, we use $stringOrderNumber
                $detailsStmt->bindValue(':orderNumber', $stringOrderNumber, PDO::PARAM_STR);
                $detailsStmt->bindValue(':userId',      $userId, PDO::PARAM_INT);
                $detailsStmt->bindValue(':productId',   $item->productid, PDO::PARAM_INT);
                $detailsStmt->bindValue(':quantity',    $item->quantity,  PDO::PARAM_INT);
                $detailsStmt->execute();
            }
        }

        // 8) Empty the cart
        $emptyCartSql = "DELETE FROM cart WHERE userid = :uid";
        $emptyCartStmt = $dbh->prepare($emptyCartSql);
        $emptyCartStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $emptyCartStmt->execute();

        // 9) Commit transaction
        $dbh->commit();

        // 10) Clear session data
        unset($_SESSION['checkoutAddressId'], $_SESSION['checkoutTotal']);

        // 11) Alert user & redirect
        echo "<script>alert('Order placed successfully! Your order number is: $stringOrderNumber');</script>";
        echo "<script>window.location.href='my-orders.php';</script>";
        exit;

    } catch (Exception $e) {
        // Roll back on error
        $dbh->rollBack();
        echo "<script>alert('Error placing order: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <!-- CSS includes (Bootstrap, etc.) -->
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
</head>
<body>
    <!-- Include your site header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcumb / page title area -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Payment</li>
            </ol>
        </div>
    </div>

    <!-- Main container for Payment -->
    <div class="container" style="margin-top:50px; margin-bottom:50px;">
        <h2>Review & Payment</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Selected Address</h4>
                <?php
                // Retrieve the chosen address from DB
                try {
                    $addrSql = "SELECT * FROM addresses WHERE id = :aid AND userId = :uid LIMIT 1";
                    $addrStmt = $dbh->prepare($addrSql);
                    $addrStmt->bindParam(':aid', $addressId, PDO::PARAM_INT);
                    $addrStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
                    $addrStmt->execute();
                    $address = ($addrStmt->rowCount() > 0) ? $addrStmt->fetch(PDO::FETCH_OBJ) : null;
                } catch (Exception $e) {
                    $address = null;
                }
                if ($address):
                ?>
                <div class="well">
                    <strong>Billing Address:</strong><br>
                    <?php echo nl2br(htmlentities($address->billingAddress)); ?><br>
                    <?php echo htmlentities($address->billingCity); ?>,
                    <?php echo htmlentities($address->billingState); ?>,
                    <?php echo htmlentities($address->billingCountry); ?><br>
                    Pincode: <?php echo htmlentities($address->billingPincode); ?>
                    <hr>
                    <strong>Shipping Address:</strong><br>
                    <?php echo nl2br(htmlentities($address->shippingAddress)); ?><br>
                    <?php echo htmlentities($address->shippingCity); ?>,
                    <?php echo htmlentities($address->shippingState); ?>,
                    <?php echo htmlentities($address->shippingCountry); ?><br>
                    Pincode: <?php echo htmlentities($address->shippingPincode); ?>
                </div>
                <?php else: ?>
                    <p>No address found. Please go back to checkout.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h4>Order Summary</h4>
                <div class="well">
                    <p><strong>Cart Total:</strong> $<?php echo htmlentities($cartTotal); ?></p>
                </div>
            </div>
        </div>

        <hr>
        <h4>Select Payment Method</h4>
        <form method="post">
            <div class="form-group">
                <label for="txntype">Payment Method</label>
                <select name="txntype" id="txntype" class="form-control" required>
                    <option value="">--Select Payment Method--</option>
                    <option value="COD">Cash on Delivery</option>
                    <option value="Online">Online Payment</option>
                </select>
            </div>
            <div class="form-group" id="transactionDiv" style="display:none;">
                <label for="txnnumber">Transaction Number</label>
                <input type="text" name="txnnumber" id="txnnumber" class="form-control">
            </div>
            <button type="submit" name="pay_now" class="btn btn-primary">Confirm & Pay</button>
        </form>
    </div>

    <!-- Include your site footer -->
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
    // Show/hide transactionNo if user chooses "Online"
    document.addEventListener('DOMContentLoaded', function() {
        var txntypeSelect = document.getElementById('txntype');
        var transactionDiv = document.getElementById('transactionDiv');
        var txnnumberField = document.getElementById('txnnumber');

        txntypeSelect.addEventListener('change', function() {
            if (this.value === 'Online') {
                transactionDiv.style.display = 'block';
                txnnumberField.required = true;
            } else {
                transactionDiv.style.display = 'none';
                txnnumberField.required = false;
            }
        });
    });
    </script>
</body>
</html>
