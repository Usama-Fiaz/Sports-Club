<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php');


if (!isset($_GET['cid']) || empty($_GET['cid'])) {
    echo "No category specified.";
    exit;
}
$cid = intval($_GET['cid']);

// Fetch category details from shopping_category table
try {
    $catSql = "SELECT id, categoryName, categoryDescription
               FROM shopping_category
               WHERE id = :cid
               LIMIT 1";
    $catStmt = $dbh->prepare($catSql);
    $catStmt->bindParam(':cid', $cid, PDO::PARAM_INT);
    $catStmt->execute();

    if ($catStmt->rowCount() === 0) {
        echo "Category not found.";
        exit;
    }
    $category = $catStmt->fetch(PDO::FETCH_OBJ);
} catch (Exception $e) {
    echo "Error fetching category: " . $e->getMessage();
    exit;
}

// Fetch products that belong to this category from the products table
try {
    $prodSql = "SELECT id, productName, productPrice, productImage1
                FROM products
                WHERE categoryId = :cid
                ORDER BY id DESC";
    $prodStmt = $dbh->prepare($prodSql);
    $prodStmt->bindParam(':cid', $cid, PDO::PARAM_INT);
    $prodStmt->execute();
    $products = $prodStmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    echo "Error fetching products: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities($category->categoryName); ?> - Products</title>
    <!-- CSS includes -->
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
        .category-products-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .product-card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            padding: 10px;
        }
        .product-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .product-card img {
            width: 100%;
            height: 200px; /* fixed height for uniform look */
            object-fit: cover;
        }
        .product-card-body {
            padding: 15px;
        }
        .product-card h5 {
            margin: 0 0 10px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .product-card p.price {
            font-size: 1.1rem;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcrumb -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop-categories.php">Shop Categories</a></li>
                <li class="active"><?php echo htmlentities($category->categoryName); ?></li>
            </ol>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container category-products-container">
        <h2><?php echo htmlentities($category->categoryName); ?></h2>
        <p><?php echo nl2br(htmlentities($category->categoryDescription)); ?></p>
        <hr>
        <div class="row">
            <?php if ($products && count($products) > 0): ?>
                <?php foreach ($products as $prod): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="product-card">
                            <?php 
                            $imgPath = !empty($prod->productImage1)
                                ? "admin/productimages/" . htmlentities($prod->productImage1)
                                : "images/placeholder.png";
                            ?>
                            <img src="<?php echo $imgPath; ?>" alt="Product Image">
                            <div class="product-card-body">
                                <h5><?php echo htmlentities($prod->productName); ?></h5>
                                <p class="price">$<?php echo htmlentities($prod->productPrice); ?></p>
                                <a href="product-details.php?pid=<?php echo $prod->id; ?>" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- JS includes -->
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.meanmenu.js"></script>
    <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
    <script src="js/nivo-slider/nivo-active.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
