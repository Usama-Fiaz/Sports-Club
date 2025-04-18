<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>SportsSync | user signin </title>
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
    <!-- animaton text css -->
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
</head>
<body>
    <!--body-wraper-are-start-->
    <div class="wrapper single-blog">
        <!--slider header area are start-->
        <div id="home" class="header-slider-area">
            <!--header start-->
            <?php include_once('includes/header.php'); ?>
            <!-- header End-->
        </div>
        <!--slider header area are end-->
        
        <!-- breadcumb-area start-->
        <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
            <div class="container">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">All Training</li>
                </ol>
            </div>
        </div> 
        <!-- breadcumb-area end-->    

        <div class="upcomming-events-area off-white ptb100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                    <?php
                    // Fetch category name from tbltrainingcategory
                    $cid = intval($_GET['catid']);
                    $sql = "SELECT id, CategoryName 
                            FROM tbltrainingcategory 
                            WHERE id = :cid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':cid', $cid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0)
                    {
                        foreach($results as $row)
                        {
                    ?>
                        <h1 class="section-title">
                            <?php echo htmlentities($row->CategoryName); ?> 
                            Category Training Details
                        </h1>
                    <?php 
                        }
                    } 
                    ?>
                    </div>
                    <div class="total-upcomming-event col-md-12 col-sm-12 col-xs-12">
                    <?php
                    // Fetching upcoming events from tblevents
                    $isactive = 1;
                    $sql = "SELECT trainingName, trainingLocation, trainingStartDate, trainingEndDate, trainingImage, id
                            FROM tbltraining
                            WHERE IsActive = :isactive
                            AND CategoryId = :cid
                            ORDER BY id DESC";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':isactive', $isactive, PDO::PARAM_STR);
                    $query->bindParam(':cid', $cid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    
                    if($query->rowCount() > 0)
                    {
                        foreach($results as $row)
                        { 
                    ?>
                            <div class="single-upcomming shadow-box">
                                <div class="col-md-4 hidden-sm col-xs-12">
                                    <div class="sue-pic">
                                        <img src="admin/trainingimages/<?php echo htmlentities($row->trainingImage); ?>" 
                                             alt="<?php echo htmlentities($row->trainingName); ?>" 
                                             style="border:#000 1px solid"> 
                                    </div>
                                    <div class="sue-date-time text-center">
                                        <span><?php echo htmlentities($row->trainingStartDate); ?></span> To
                                        <span><?php echo htmlentities($row->trainingEndDate); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-5 col-xs-12">
                                    <div class="uc-event-title">
                                        <div class="uc-icon"><i class="zmdi zmdi-globe-alt"></i></div>
                                        <a href="#"><?php echo htmlentities($row->trainingName); ?></a>
                                    </div> 
                                </div> 
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <div class="venu-no">
                                        <p>Location : <?php echo htmlentities($row->trainingLocation); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <div class="upcomming-ticket text-center">
                                        <a href="training-details.php?evntid=<?php echo htmlentities($row->id); ?>" 
                                           class="btn-def bnt-2 small">View Details</a>
                                    </div>
                                </div>
                            </div>
                    <?php 
                        }
                    } 
                    else 
                    { 
                    ?>                
                        <p>No Record Found</p>    
                    <?php 
                    } 
                    ?>
                        <hr />
                    </div>
                </div>
            </div>
        </div>               
        <!-- upcomming events area --> 

        <!-- information area start -->
        <?php include_once('includes/footer.php'); ?>
        <!-- footer area end -->
    </div>
    <!-- body-wraper-are-end -->

    <!--==== all js here====-->
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
