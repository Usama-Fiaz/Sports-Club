<?php
session_start();
error_reporting(0);
include('includes/config.php'); // PDO connection in $dbh

// Ensure user is logged in
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: logout.php");
    exit;
}
$userId = $_SESSION['usrid'];

// // Handle Add New Address form submission

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
        $sql = "INSERT INTO addresses
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
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':uid',     $userId,           PDO::PARAM_INT);
        $stmt->bindParam(':bAddr',   $billingAddress,   PDO::PARAM_STR);
        $stmt->bindParam(':bCity',   $billingCity,      PDO::PARAM_STR);
        $stmt->bindParam(':bState',  $billingState,     PDO::PARAM_STR);
        $stmt->bindParam(':bCountry',$billingCountry,   PDO::PARAM_STR);
        $stmt->bindParam(':bPin',    $billingPincode,   PDO::PARAM_STR);
        $stmt->bindParam(':sAddr',   $shippingAddress,  PDO::PARAM_STR);
        $stmt->bindParam(':sCity',   $shippingCity,     PDO::PARAM_STR);
        $stmt->bindParam(':sState',  $shippingState,    PDO::PARAM_STR);
        $stmt->bindParam(':sCountry',$shippingCountry,  PDO::PARAM_STR);
        $stmt->bindParam(':sPin',    $shippingPincode,  PDO::PARAM_STR);
        $stmt->execute();

        echo "<script>alert('Address added successfully');</script>";
        echo "<script>window.location.href='manage-address.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error adding address: " . addslashes($e->getMessage()) . "');</script>";
    }
}

//  Handle Update Address form submission (editing an existing address)
if (isset($_POST['update_address'])) {
    $editId           = intval($_POST['editId']);
    $billingAddress   = $_POST['edit_billingAddress']   ?? '';
    $billingCity      = $_POST['edit_billingCity']      ?? '';
    $billingState     = $_POST['edit_billingState']     ?? '';
    $billingCountry   = $_POST['edit_billingCountry']   ?? '';
    $billingPincode   = $_POST['edit_billingPincode']   ?? '';

    $shippingAddress  = $_POST['edit_shippingAddress']  ?? '';
    $shippingCity     = $_POST['edit_shippingCity']     ?? '';
    $shippingState    = $_POST['edit_shippingState']    ?? '';
    $shippingCountry  = $_POST['edit_shippingCountry']  ?? '';
    $shippingPincode  = $_POST['edit_shippingPincode']  ?? '';

    try {
        $sql = "UPDATE addresses
                SET
                  billingAddress   = :bAddr,
                  billingCity      = :bCity,
                  billingState     = :bState,
                  billingCountry   = :bCountry,
                  billingPincode   = :bPin,
                  shippingAddress  = :sAddr,
                  shippingCity     = :sCity,
                  shippingState    = :sState,
                  shippingCountry  = :sCountry,
                  shippingPincode  = :sPin
                WHERE id = :addrId
                  AND userId = :uid
                LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':bAddr',   $billingAddress,   PDO::PARAM_STR);
        $stmt->bindParam(':bCity',   $billingCity,      PDO::PARAM_STR);
        $stmt->bindParam(':bState',  $billingState,     PDO::PARAM_STR);
        $stmt->bindParam(':bCountry',$billingCountry,   PDO::PARAM_STR);
        $stmt->bindParam(':bPin',    $billingPincode,   PDO::PARAM_STR);
        $stmt->bindParam(':sAddr',   $shippingAddress,  PDO::PARAM_STR);
        $stmt->bindParam(':sCity',   $shippingCity,     PDO::PARAM_STR);
        $stmt->bindParam(':sState',  $shippingState,    PDO::PARAM_STR);
        $stmt->bindParam(':sCountry',$shippingCountry,  PDO::PARAM_STR);
        $stmt->bindParam(':sPin',    $shippingPincode,  PDO::PARAM_STR);
        $stmt->bindParam(':addrId',  $editId,           PDO::PARAM_INT);
        $stmt->bindParam(':uid',     $userId,           PDO::PARAM_INT);
        $stmt->execute();

        echo "<script>alert('Address updated successfully');</script>";
        echo "<script>window.location.href='manage-address.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error updating address: " . addslashes($e->getMessage()) . "');</script>";
    }
}


// Fetch all addresses for this user
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


// If user clicked "Edit" (i.e. URL has ?editid=...), fetch that address for editing
$editAddress = null;
if (isset($_GET['editid'])) {
    $editId = intval($_GET['editid']);
    try {
        $editSql = "SELECT * FROM addresses WHERE id = :eid AND userId = :uid LIMIT 1";
        $editStmt = $dbh->prepare($editSql);
        $editStmt->bindParam(':eid', $editId, PDO::PARAM_INT);
        $editStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $editStmt->execute();
        if ($editStmt->rowCount() > 0) {
            $editAddress = $editStmt->fetch(PDO::FETCH_OBJ);
        }
    } catch (Exception $e) {
        $editAddress = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Addresses</title>
    <!-- CSS -->
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
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcumb -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Manage Addresses</li>
            </ol>
        </div>
    </div>

    <div class="container" style="margin-top:50px; margin-bottom:50px;">
        <h2>Your Saved Addresses</h2>
        <?php if (count($userAddresses) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Billing Address</th>
                            <th>Billing City</th>
                            <th>Billing State</th>
                            <th>Billing Country</th>
                            <th>Billing Pincode</th>
                            <th>Shipping Address</th>
                            <th>Shipping City</th>
                            <th>Shipping State</th>
                            <th>Shipping Country</th>
                            <th>Shipping Pincode</th>
                            <th>Action</th>
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
                            <td>
                                <!-- Edit link -->
                                <a href="manage-address.php?editid=<?php echo $addr->id; ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No addresses found. Add a new address below.</p>
        <?php endif; ?>

        <hr>

        <?php if ($editAddress): ?>
            <!-- Edit Form (when ?editid= is present) -->
            <h2>Edit Address (ID: <?php echo htmlentities($editAddress->id); ?>)</h2>
            <form method="post">
                <input type="hidden" name="editId" value="<?php echo $editAddress->id; ?>">
                <div class="row">
                    <!-- Left: Billing -->
                    <div class="col-md-6">
                        <h4>Billing</h4>
                        <div class="form-group">
                            <label for="edit_billingAddress">Billing Address</label>
                            <textarea name="edit_billingAddress" id="edit_billingAddress" class="form-control" required><?php echo htmlentities($editAddress->billingAddress); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_billingCity">Billing City</label>
                            <input type="text" name="edit_billingCity" id="edit_billingCity" class="form-control" required value="<?php echo htmlentities($editAddress->billingCity); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_billingState">Billing State</label>
                            <input type="text" name="edit_billingState" id="edit_billingState" class="form-control" required value="<?php echo htmlentities($editAddress->billingState); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_billingCountry">Billing Country</label>
                            <input type="text" name="edit_billingCountry" id="edit_billingCountry" class="form-control" required value="<?php echo htmlentities($editAddress->billingCountry); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_billingPincode">Billing Pincode</label>
                            <input type="text" name="edit_billingPincode" id="edit_billingPincode" class="form-control" required value="<?php echo htmlentities($editAddress->billingPincode); ?>">
                        </div>
                    </div>

                    <!-- Right: Shipping -->
                    <div class="col-md-6">
                        <h4>Shipping</h4>
                        <div class="form-group">
                            <label for="edit_shippingAddress">Shipping Address</label>
                            <textarea name="edit_shippingAddress" id="edit_shippingAddress" class="form-control" required><?php echo htmlentities($editAddress->shippingAddress); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_shippingCity">Shipping City</label>
                            <input type="text" name="edit_shippingCity" id="edit_shippingCity" class="form-control" required value="<?php echo htmlentities($editAddress->shippingCity); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_shippingState">Shipping State</label>
                            <input type="text" name="edit_shippingState" id="edit_shippingState" class="form-control" required value="<?php echo htmlentities($editAddress->shippingState); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_shippingCountry">Shipping Country</label>
                            <input type="text" name="edit_shippingCountry" id="edit_shippingCountry" class="form-control" required value="<?php echo htmlentities($editAddress->shippingCountry); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_shippingPincode">Shipping Pincode</label>
                            <input type="text" name="edit_shippingPincode" id="edit_shippingPincode" class="form-control" required value="<?php echo htmlentities($editAddress->shippingPincode); ?>">
                        </div>
                    </div>
                </div>
                <button type="submit" name="update_address" class="btn btn-success">Update Address</button>
                <a href="manage-address.php" class="btn btn-default">Cancel</a>
            </form>
        <?php else: ?>
            <!-- Add New Address Form -->
            <h2>Add a New Address</h2>
            <form method="post">
                <div class="row">
                    <!-- Left: Billing -->
                    <div class="col-md-6">
                        <h4>Billing</h4>
                        <div class="form-group">
                            <label for="billingAddress">Billing Address</label>
                            <textarea name="billingAddress" id="billingAddress" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="billingCity">Billing City</label>
                            <input type="text" name="billingCity" id="billingCity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingState">Billing State</label>
                            <input type="text" name="billingState" id="billingState" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingCountry">Billing Country</label>
                            <input type="text" name="billingCountry" id="billingCountry" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="billingPincode">Billing Pincode</label>
                            <input type="text" name="billingPincode" id="billingPincode" class="form-control" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" id="sameAsBilling" class="form-check-input">
                            <label for="sameAsBilling" class="form-check-label">Shipping same as Billing</label>
                        </div>
                    </div>
                    <!-- Right: Shipping -->
                    <div class="col-md-6">
                        <h4>Shipping</h4>
                        <div class="form-group">
                            <label for="shippingAddress">Shipping Address</label>
                            <textarea name="shippingAddress" id="shippingAddress" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="shippingCity">Shipping City</label>
                            <input type="text" name="shippingCity" id="shippingCity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingState">Shipping State</label>
                            <input type="text" name="shippingState" id="shippingState" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingCountry">Shipping Country</label>
                            <input type="text" name="shippingCountry" id="shippingCountry" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="shippingPincode">Shipping Pincode</label>
                            <input type="text" name="shippingPincode" id="shippingPincode" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="add_address" class="btn btn-primary">Save Address</button>
            </form>
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
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.mb.YTPlayer.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.nav.js"></script>
    <script src="js/animate-text.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- jQuery to copy billing to shipping if checkbox is checked -->
    <script>
    $(document).ready(function() {
        $('#sameAsBilling').on('change', function() {
            if ($(this).is(':checked')) {
                $('#shippingAddress').val($('#billingAddress').val());
                $('#shippingCity').val($('#billingCity').val());
                $('#shippingState').val($('#billingState').val());
                $('#shippingCountry').val($('#billingCountry').val());
                $('#shippingPincode').val($('#billingPincode').val());
            } else {
                $('#shippingAddress').val('');
                $('#shippingCity').val('');
                $('#shippingState').val('');
                $('#shippingCountry').val('');
                $('#shippingPincode').val('');
            }
        });
    });
    </script>
</body>
</html>
