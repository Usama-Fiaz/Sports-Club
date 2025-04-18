<?php

session_start();
include('includes/config.php'); 

// Check if user is logged in; if not, redirect them
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: logout.php");
    exit;
}

$userId = $_SESSION['usrid'];

// If a deletion is requested via GET parameter 'del', remove that wishlist item for this user.
if (isset($_GET['del']) && !empty($_GET['del'])) {
    $delId = intval($_GET['del']);
    try {
        // Ensure that the wishlist record belongs to the logged in user
        $delSql = "DELETE FROM wishlist WHERE id = :wid AND userId = :uid";
        $delStmt = $dbh->prepare($delSql);
        $delStmt->bindParam(':wid', $delId, PDO::PARAM_INT);
        $delStmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $delStmt->execute();
        echo "<script>alert('Product removed from your wishlist');</script>";
        echo "<script>window.location.href='my-wishlist.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error removing product: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Fetch wishlist items for this user by joining the wishlist table with the products table.
try {
    $sql = "SELECT w.id as wishlistId, p.id as productId, p.productName, p.productPrice, p.productImage1
            FROM wishlist w
            LEFT JOIN products p ON w.productId = p.id
            WHERE w.userId = :uid
            ORDER BY w.id DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $wishlistItems = ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>

    <!-- CSS Files (Bootstrap, etc.) -->
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

    <!-- Inline CSS for Wishlist Layout -->
    <style>
        .wishlist-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .wishlist-table {
            margin-top: 30px;
        }
        .wishlist-table th, .wishlist-table td {
            vertical-align: middle;
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
        /* Responsive image in table */
        .prod-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Header: Ensure it shows "Welcome [User]" in header (handled in header.php) -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- Breadcumb Area -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">My Wishlist</li>
            </ol>
        </div>
    </div>

    <!-- Main Content: Wishlist Items -->
    <div class="container wishlist-container">
        <h1 class="section-title">My Wishlist</h1>
        <?php if (count($wishlistItems) > 0): ?>
            <div class="table-responsive wishlist-table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wishlistItems as $item): 
                            // Use productImage1; if empty, fallback to placeholder
                            $imgFile = $item->productImage1 ?? '';
                            $prodImg = !empty($imgFile) ? "admin/productimages/" . $imgFile : "images/placeholder.png";
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlentities($prodImg); ?>" alt="Product Image" class="prod-img">
                            </td>
                            <td><?php echo htmlentities($item->productName); ?></td>
                            <td>$<?php echo htmlentities($item->productPrice); ?></td>
                            <td>
                                <!-- Remove item link -->
                                <a href="my-wishlist.php?del=<?php echo $item->wishlistId; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this product from your wishlist?');">Remove</a>
                                <!-- View Details link -->
                                <a href="product-details.php?pid=<?php echo $item->productId; ?>" class="btn btn-black btn-sm">View Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center">
                <h4>You have no items in your wishlist.</h4>
                <a href="index.php" class="btn btn-success">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- JS Files -->
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
</body>
</html>
