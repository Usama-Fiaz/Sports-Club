<?php
session_start();
error_reporting(0);
include('includes/config.php'); // PDO connection in $dbh

// 1. Fetch categories from the 'shopping_category' table
$sql = "SELECT id, categoryName, categoryDescription, categoryImage
        FROM shopping_category
        ORDER BY id DESC";
$query = $dbh->prepare($sql);
$query->execute();

// 2. Store results in an array (or empty if none)
if ($query->rowCount() > 0) {
    $results = $query->fetchAll(PDO::FETCH_OBJ);
} else {
    $results = [];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Categories</title>

    <!-- ======== CSS Files ======== -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- meanmenu css -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <!-- icofont css -->
    <link rel="stylesheet" href="css/icofont.css">
    <!-- Nivo css -->
    <link rel="stylesheet" href="css/nivo-slider.css">
    <!-- animate-text css -->
    <link rel="stylesheet" href="css/animate-text.css">
    <!-- Metrial iconic fonts css -->
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- color css -->
    <link href="css/color/skin-default.css" rel="stylesheet">
    <!-- modernizr css -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <!-- Custom Inline CSS for Equal-Height Cards -->
    <style>
        /* Container spacing */
        .shop-categories-container {
            margin-top: 100px; /* push below header */
            margin-bottom: 50px;
        }
        /* Make the row a flex container, forcing equal heights in each row */
        .row-eq-height {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch; /* ensures columns align at the top */
            margin-right: -15px;  /* match .row negative margins */
            margin-left: -15px;
        }
        /* Each column is also a flex container so the .card can fill its height */
        .row-eq-height [class*='col-'] {
            display: flex;
            flex-direction: column;
            padding-left: 15px;
            padding-right: 15px;
            margin-bottom: 20px; /* spacing between rows */
        }
        /* Card styling */
        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            flex: 1 1 auto; /* ensures the card can fill the column height */
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Fixed image height for uniform look */
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            flex: 0 0 auto; /* so the image doesn't stretch */
        }

        /* Card body takes remaining vertical space */
        .card-body {
            flex: 1 1 auto;  /* fill the remaining space */
            display: flex;
            flex-direction: column; /* so we can push the button to bottom */
        }
        .card-body h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 0;
        }
        .card-body p {
            font-size: 0.95rem;
            color: #555;
            flex: 0 0 auto;
        }
        /* Button at the bottom of card body */
        .card-body a.btn {
            margin-top: auto;
        }

        /* Custom black button class */
        .btn-black {
            background-color: #000 !important;
            color: #fff !important;
            border: none !important;
            padding: 10px 20px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .btn-black:hover {
            background-color: #333 !important;
        }
    </style>
</head>
<body>
    <!-- ========== Header ========== -->
    <?php include_once('includes/header.php'); ?>

    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Shop Categories</li>
            </ol>
        </div>
    </div>

    <!-- ========== Main Content ========== -->
    <div class="container shop-categories-container">
        <h1 class="text-center mb-4">Shop Categories</h1>

        <!-- 
          .row-eq-height forces columns to stretch equally in a row.
          col-md-4 for 3 columns on medium+ screens, col-sm-6 for 2 columns on smaller screens.
        -->
        <div class="row-eq-height">
            <?php
            if (count($results) > 0) {
                foreach ($results as $row) {
                    // Construct the image path or fallback to placeholder
                    $catImg = (!empty($row->categoryImage))
                        ? "admin/categoryimages/" . $row->categoryImage
                        : "images/placeholder.png";
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card">
                            <img class="card-img-top" src="<?php echo htmlentities($catImg); ?>" alt="Category Image" />
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo htmlentities($row->categoryName); ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo htmlentities($row->categoryDescription); ?>
                                </p>
                                <!-- Changed from .btn-primary to .btn-black -->
                                <a href="categorywise-products.php?cid=<?php echo $row->id; ?>" 
                                   class="btn btn-black">
                                   View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12'><p>No categories found.</p></div>";
            }
            ?>
        </div>
    </div>

    <!-- ========== Footer ========== -->
    <?php include_once('includes/footer.php'); ?>

    <!-- ======== JS Files (from your project) ======== -->
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
