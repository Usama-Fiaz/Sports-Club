<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Must create your PDO connection in $dbh

?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SportsSync | Registered Clubs</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ========== CSS from your project ========== -->
    <link rel="shortcut icon" type="image/x-icon" href="img/icon/favicon.ico">
    <!-- bootstrap v3.3.6 css -->
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

    <!-- Optional Inline CSS for a basic card layout -->
    <style>
        /* Basic styling for the "club card" */
        .club-card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background: #fff;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .club-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .club-card img {
            width: 100%;
            height: 180px; /* fixed height for the logo */
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        .club-card-body {
            padding: 15px;
        }
        .club-card-body h4 {
            margin-top: 0;
            font-weight: 600;
        }
        .club-card-body p {
            margin-bottom: 5px;
            color: #555;
        }
        .club-card-body .status {
            font-weight: bold;
            color: #008000; /* green for active */
        }
        .club-card-body .status.inactive {
            color: #c00; /* red for inactive */
        }
    </style>
</head>
<body>
<!-- ========== Wrapper Start ========== -->
<div class="wrapper single-blog">

    <!-- ========== Header Section ========== -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- ========== Breadcumb Section ========== -->
    <div class="breadcumb-area bg-overlay"
         style="background: #333; 
                background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Registered Clubs</li>
            </ol>
        </div>
    </div>

    <!-- ========== Main Content ========== -->
    <div class="single-blog-area ptb100 fix">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="section-title">Registered Clubs</h1>
                </div>
            </div>

            <div class="row">
                <?php
                // Fetch all clubs from tblclub (adjust column names if needed)
                $sql = "SELECT 
                            id,
                            clubName,
                            clubDescription,
                            clubEmail,
                            clubContact,
                            clubAddress,
                            clubLogo,
                            creationDate,
                            updationDate,
                            IsActive
                        FROM tblclub
                        ORDER BY id DESC";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if($query->rowCount() > 0) {
                    foreach($results as $row) {
                        // Build the club logo path (if you want to show the logo)
                        $logoPath = (!empty($row->clubLogo))
                            ? "clubimages/" . htmlentities($row->clubLogo)
                            : "images/placeholder.png"; // fallback image
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="club-card">
                        <img src="<?php echo $logoPath; ?>" alt="Club Logo">
                        <div class="club-card-body">
                            <h4><?php echo htmlentities($row->clubName); ?></h4>
                            <p><?php echo htmlentities($row->clubDescription); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlentities($row->clubEmail); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlentities($row->clubContact); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlentities($row->clubAddress); ?></p>
                            <p>
                                <strong>Status:</strong>
                                <?php if ($row->IsActive == 1) { ?>
                                    <span class="status">Active</span>
                                <?php } else { ?>
                                    <span class="status inactive">Inactive</span>
                                <?php } ?>
                            </p>
                            <p><small>Created on <?php echo htmlentities($row->creationDate); ?></small></p>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    // No clubs found
                    echo "<div class='col-xs-12'><p>No clubs found.</p></div>";
                }
                ?>
            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /single-blog-area -->

    <!-- ========== Footer ========== -->
    <?php include_once('includes/footer.php'); ?>

</div>
<!-- ========== Wrapper End ========== -->

<!-- ====== JS Files (from your project) ====== -->
<!-- jquery latest version -->
<script src="js/vendor/jquery-3.1.1.min.js"></script>
<!-- bootstrap js -->
<script src="js/bootstrap.min.js"></script>
<!-- owl.carousel js -->
<script src="js/owl.carousel.min.js"></script>
<!-- meanmenu js -->
<script src="js/jquery.meanmenu.js"></script>
<!-- Nivo js -->
<script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
<script src="js/nivo-slider/nivo-active.js"></script>
<!-- wow js -->
<script src="js/wow.min.js"></script>
<!-- Youtube Background JS -->
<script src="js/jquery.mb.YTPlayer.min.js"></script>
<!-- datepicker js -->
<script src="js/bootstrap-datepicker.js"></script>
<!-- waypoint js -->
<script src="js/waypoints.min.js"></script>
<!-- onepage nav js -->
<script src="js/jquery.nav.js"></script>
<!-- animate text JS -->
<script src="js/animate-text.js"></script>
<!-- plugins js -->
<script src="js/plugins.js"></script>
<!-- main js -->
<script src="js/main.js"></script>
</body>
</html>
