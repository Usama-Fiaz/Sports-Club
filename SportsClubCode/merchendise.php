<?php

session_start();
include('includes/config.php'); // Must create a PDO connection in $dbh

// 2. Pagination settings
$limit = 8; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 3. Get total count of products (for pagination)
$totalSql = "SELECT COUNT(*) AS total FROM products";
$totalStmt = $dbh->prepare($totalSql);
$totalStmt->execute();
$totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
$totalProducts = $totalRow['total'] ?? 0;
$totalPages = ceil($totalProducts / $limit);

try {
    // 4. Fetch products (with LIMIT for pagination)
    $sql = "SELECT id, productName, productPrice, productImage1
            FROM products
            ORDER BY id DESC
            LIMIT :offset, :limit";
    $query = $dbh->prepare($sql);
    // Bind parameters as integers
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->execute();

    // 5. Fetch results (or empty if none)
    $results = ($query->rowCount() > 0) ? $query->fetchAll(PDO::FETCH_OBJ) : [];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// (Optional) Fix 'uname' undefined warnings in header
if (!isset($_SESSION['uname'])) {
    $_SESSION['uname'] = "";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>

    <!-- ========== CSS ========== -->
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

    <!-- ========== Inline CSS for Cards, Flex, and Pagination ========== -->
    <style>
        /* Breadcumb overlay area (like your events page) */
        .breadcumb-area.bg-overlay { /* background/gradient if any */ }

        /* upcomming-events-area styling from events page */
        .upcomming-events-area.off-white.ptb100 { /* background/padding as needed */ }

        /* Row & column flex for uniform card heights */
        .row.d-flex.align-items-stretch {
            flex-wrap: wrap;
        }
        .col-md-3.col-sm-6.mb-4.d-flex {
            display: flex;
        }

        /* Card layout (flex column) */
        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            flex: 1 1 auto;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card-img-top {
            width: 100%;
            height: 220px; /* uniform height */
            object-fit: cover;
            flex: 0 0 auto;
        }
        .card-body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
        .card-body h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 0;
        }
        .card-body .price {
            font-size: 1rem;
            font-weight: 700;
            color: #000;
            margin: 10px 0;
        }
        .btn-black {
            background-color: #000 !important;
            color: #fff !important;
            border: none !important;
            margin-top: auto; /* push button to bottom */
            text-transform: uppercase;
        }
        .btn-black:hover {
            background-color: #333 !important;
        }

        /* Pagination styling (Bootstrap) */
        .pagination {
            margin-top: 30px;
        }
        .pagination .page-item.active .page-link {
            background-color: #000;
            border-color: #000;
        }
    </style>
</head>
<body>
    <!-- ====== Header ====== -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- ====== Breadcumb ====== -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">All Products</li>
            </ol>
        </div>
    </div>

    <!-- ====== Main Content ====== -->
    <div class="upcomming-events-area off-white ptb100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="section-title">All-Products</h1>
                </div>
            </div>
            <!-- Products Grid -->
            <div class="row d-flex align-items-stretch">
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $row): 
                        // Avoid null issues by coalescing
                        $pName  = $row->productName  ?? '';
                        $pPrice = $row->productPrice ?? 0;
                        // If productImage1 is empty, fallback to placeholder
                        $pImgFile = $row->productImage1 ?? '';
                        $pImg = (!empty($pImgFile))
                            ? "admin/productimages/" . $pImgFile
                            : "images/placeholder.png";
                    ?>
                    <div class="col-md-3 col-sm-6 mb-4 d-flex">
                        <div class="card">
                            <img class="card-img-top" src="<?php echo htmlentities($pImg); ?>" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlentities($pName); ?></h5>
                                <div class="price">$<?php echo htmlentities($pPrice); ?></div>
                                <a href="product-details.php?pid=<?php echo $row->id; ?>" class="btn btn-black">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p>No products found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination (only if more than 1 page) -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Products pagination">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
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
