<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('includes/config.php'); // Must create a PDO connection in $dbh

// If user is not logged in, either show a message or redirect to login
if (!isset($_SESSION['usrid']) || $_SESSION['usrid'] == 0) {
    echo "<script>alert('You must be logged in to view your cart.');</script>";
    echo "<script>window.location.href='signin.php';</script>";
    exit;
}

$uid = $_SESSION['usrid'];

// ===== Handle Delete Cart Item =====
if (isset($_GET['delid']) && !empty($_GET['delid'])) {
    $cartId = intval($_GET['delid']);
    // Delete the cart item for this user
    $delSql = "DELETE FROM cart WHERE id = :cartId AND userId = :uid";
    $delStmt = $dbh->prepare($delSql);
    $delStmt->bindParam(':cartId', $cartId, PDO::PARAM_INT);
    $delStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $delStmt->execute();
    // Refresh page after deletion
    echo "<script>window.location.href='my-cart.php';</script>";
    exit;
}

// ===== Fetch Cart Items for This User =====
try {
    // Join cart + products
    $sql = "SELECT c.id AS cartId, c.quantity, p.id AS pid, p.productName, p.productPrice, p.productImage1
            FROM cart c
            JOIN products p ON c.productId = p.id
            WHERE c.userId = :uid
            ORDER BY c.id DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Calculate grand total
$grandTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <!-- ========== CSS  ========== -->
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

    <!-- Inline CSS for cart table styling -->
    <style>
        .cart-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        table.cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.cart-table th, table.cart-table td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
        }
        table.cart-table th {
            background: #f2f2f2;
        }
        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .cart-actions {
            margin-top: 20px;
            text-align: right;
        }
        .btn-black {
            background-color: #000 !important;
            color: #fff !important;
            border: none !important;
            text-transform: uppercase;
        }
        .btn-black:hover {
            background-color: #333 !important;
        }
    </style>
</head>
<body>
    <!-- ====== Header ====== -->
    <?php include_once('includes/header.php'); ?>

    <!-- ====== Breadcumb ====== -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Cart</li>
            </ol>
        </div>
    </div>

    <!-- ====== Cart Container ====== -->
    <div class="container cart-container">
        <h1>My Cart</h1>
        <?php if (count($cartItems) > 0): ?>
            <div class="table-responsive">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): 
                            $pImgFile = $item->productImage1 ?? '';
                            $pImg = !empty($pImgFile) ? "admin/productimages/" . $pImgFile : "images/placeholder.png";

                            // Subtotal
                            $subTotal = $item->productPrice * $item->quantity;
                            $grandTotal += $subTotal;
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlentities($pImg); ?>" alt="Product Image" class="cart-img">
                            </td>
                            <td><?php echo htmlentities($item->productName); ?></td>
                            <td>$<?php echo htmlentities($item->productPrice); ?></td>
                            <td><?php echo htmlentities($item->quantity); ?></td>
                            <td>$<?php echo $subTotal; ?></td>
                            <td>
                                <a href="my-cart.php?delid=<?php echo $item->cartId; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <h3 style="margin-top: 20px;">Grand Total: $<?php echo $grandTotal; ?></h3>
            <div class="cart-actions">
                <a href="merchendise.php" class="btn btn-black">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-warning">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="merchendise.php" class="btn btn-black">Continue Shopping</a>
        <?php endif; ?>
    </div>

    <!-- ====== Footer ====== -->
    <?php include_once('includes/footer.php'); ?>

    <!-- ====== JS ====== -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
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
</body>
</html>
