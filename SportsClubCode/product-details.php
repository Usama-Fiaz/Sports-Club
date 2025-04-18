<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('includes/config.php'); 

// 1. Check if a product ID is provided
if (!isset($_GET['pid']) || empty($_GET['pid'])) {
    echo "No product specified.";
    exit;
}
$pid = intval($_GET['pid']);

// 2. Fetch product details (adjust subCategory references if needed)
try {
    $sql = "SELECT p.*, s.subCategoryName, s.id AS subCatId
            FROM products p
            LEFT JOIN sub_category s ON p.subCategoryId = s.id
            WHERE p.id = :pid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $product = $stmt->fetch(PDO::FETCH_OBJ);
    } else {
        echo "Product not found.";
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// 3. Handle Add to Wishlist / Add to Cart
$wishlist_msg = "";
$cart_msg = "";
$login_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in 
    if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
        $login_msg = "Please log in to perform this action.";
    } else {
        $uid = $_SESSION['usrid'];

        // Add to Wishlist
        if (isset($_POST['add_to_wishlist'])) {

            $checkSql = "SELECT id FROM wishlist WHERE userid = :uid AND productid = :pid";
            $checkStmt = $dbh->prepare($checkSql);
            $checkStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
            $checkStmt->bindParam(':pid', $pid, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $wishlist_msg = "Product is already in your wishlist.";
            } else {
                $insertSql = "INSERT INTO wishlist (userid, productid) VALUES (:uid, :pid)";
                $insertStmt = $dbh->prepare($insertSql);
                $insertStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
                $insertStmt->bindParam(':pid', $pid, PDO::PARAM_INT);
                if ($insertStmt->execute()) {
                    $wishlist_msg = "Product added to your wishlist.";
                } else {
                    $wishlist_msg = "Failed to add product to wishlist.";
                }
            }
        }

        // Add to Cart
        if (isset($_POST['add_to_cart'])) {
         
            $checkSql = "SELECT id FROM cart WHERE userid = :uid AND productid = :pid";
            $checkStmt = $dbh->prepare($checkSql);
            $checkStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
            $checkStmt->bindParam(':pid', $pid, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $cart_msg = "Product is already in your cart.";
            } else {
                // Insert a row with quantity = 1 by default
                $insertSql = "INSERT INTO cart (userid, productid, quantity) VALUES (:uid, :pid, 1)";
                $insertStmt = $dbh->prepare($insertSql);
                $insertStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
                $insertStmt->bindParam(':pid', $pid, PDO::PARAM_INT);
                if ($insertStmt->execute()) {
                    $cart_msg = "Product added to your cart.";
                } else {
                    $cart_msg = "Failed to add product to cart.";
                }
            }
        }
    }
}

// 4. Fetch related products 
$relatedProducts = [];
if (!empty($product->subCategoryId)) {
    try {
        $subCatId = $product->subCategoryId;

        $relSql = "SELECT id, productName, productPrice, productImage1
                   FROM products
                   WHERE subCategoryId = :subCatId
                     AND id <> :pid
                   ORDER BY id DESC
                   LIMIT 4";
        $relStmt = $dbh->prepare($relSql);
        $relStmt->bindParam(':subCatId', $subCatId, PDO::PARAM_INT);
        $relStmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $relStmt->execute();
        $relatedProducts = $relStmt->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        $relatedProducts = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities($product->productName); ?> - Product Details</title>
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

    <style>
        .product-details-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .product-info h2 {
            font-size: 2rem;
            margin-top: 0;
        }
        .product-info .price {
            font-size: 1.5rem;
            color: #000;
            font-weight: bold;
            margin: 10px 0;
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
        .related-products .card {
            margin-bottom: 20px;
        }
        .related-products .card-img-top {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- Breadcumb area -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Product Details</li>
            </ol>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="container product-details-container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <?php 
                $imgFile = $product->productImage1 ?? '';
                $prodImg = (!empty($imgFile)) ? "admin/productimages/" . $imgFile : "images/placeholder.png";
                ?>
                <img src="<?php echo htmlentities($prodImg); ?>" alt="Product Image" class="product-image img-responsive">
            </div>

            <!-- Product Info -->
            <div class="col-md-6 product-info">
                <h2><?php echo htmlentities($product->productName); ?></h2>
                <div class="price">$<?php echo htmlentities($product->productPrice); ?></div>
                <p>
                    <?php 
                    echo nl2br(htmlentities($product->productDescription ?? 'No description.'));
                    ?>
                </p>
                <p>
                    <strong>Shipping Details:</strong>
                    <?php echo nl2br(htmlentities($product->shippingDetails ?? 'Not specified.')); ?>
                </p>

                <!-- Show messages -->
                <?php if(!empty($wishlist_msg)): ?>
                    <div class="alert alert-info"><?php echo $wishlist_msg; ?></div>
                <?php endif; ?>
                <?php if(!empty($cart_msg)): ?>
                    <div class="alert alert-info"><?php echo $cart_msg; ?></div>
                <?php endif; ?>
                <?php if(!empty($login_msg)): ?>
                    <div class="alert alert-warning"><?php echo $login_msg; ?></div>
                <?php endif; ?>

                <!-- Forms for wishlist & cart -->
                <form method="post">
                    <button type="submit" name="add_to_wishlist" class="btn btn-black">Add to Wishlist</button>
                    <button type="submit" name="add_to_cart" class="btn btn-black">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if(count($relatedProducts) > 0): ?>
    <div class="container related-products" style="margin-bottom:50px;">
        <h3>Related Products</h3>
        <div class="row d-flex align-items-stretch">
            <?php foreach($relatedProducts as $rprod):
                $rImgFile = $rprod->productImage1 ?? '';
                $rProdImg = (!empty($rImgFile)) ? "admin/productimages/" . $rImgFile : "images/placeholder.png";
            ?>
            <div class="col-md-3 col-sm-6 mb-4 d-flex">
                <div class="card flex-fill">
                    <img class="card-img-top" src="<?php echo htmlentities($rProdImg); ?>" alt="Related Product">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlentities($rprod->productName); ?></h5>
                        <div class="price">$<?php echo htmlentities($rprod->productPrice); ?></div>
                        <a href="product-details.php?pid=<?php echo $rprod->id; ?>" class="btn btn-black">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- JS -->
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
