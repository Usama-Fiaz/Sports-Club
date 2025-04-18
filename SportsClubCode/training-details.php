<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Booking Process for Training
if(isset($_POST['book']))
{
    $bookingid   = mt_rand(100000000, 999999999);
    $userid      = $_SESSION['usrid'];
    $tid         = intval($_GET['trnid']);
    // Getting Post values
    $noofmembers = $_POST['noofmembers'];
    $usrremark   = $_POST['userremark'];

    // Query for data insertion into tbltrainingbookings
    $sql = "INSERT INTO tbltrainingbookings(
                BookingId,
                UserId,
                TrainingId,
                NumberOfMembers,
                UserRemark
            ) VALUES(
                :bookingid,
                :userid,
                :tid,
                :noofmembers,
                :usrremark
            )";
    // preparing the query
    $query = $dbh->prepare($sql);
    // Binding the values
    $query->bindParam(':bookingid',   $bookingid,   PDO::PARAM_STR);
    $query->bindParam(':userid',      $userid,      PDO::PARAM_STR);
    $query->bindParam(':tid',         $tid,         PDO::PARAM_STR);
    $query->bindParam(':noofmembers', $noofmembers, PDO::PARAM_STR);
    $query->bindParam(':usrremark',   $usrremark,   PDO::PARAM_STR);
    // Execute the query
    $query->execute();
    // Check that the insertion really worked
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
        echo '<script>alert("Training booked successfully. Booking number is '.$bookingid.'")</script>';
        // Redirect to bookings page (adjust if you have a different page name)
        echo "<script>window.location.href='my-bookings.php'</script>";  
    }
    else 
    {
        echo "<script>alert('Error : Something went wrong. Please try again');</script>";   
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Training Details</title>
    <!-- all css here -->
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
    <style>
        /* Custom Styles for Training Details Page */
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .about-area {
            padding: 60px 0;
        }
        .about-left, .about-right {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            padding: 35px;
        }
        .about-left h1.section-title {
            font-size: 30px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 20px;
        }
        .about-left h3 {
            font-size: 20px;
            font-weight: 600;
            margin-top: 25px;
        }
        .about-left .sub-title {
            font-size: 17px;
            font-weight: 500;
            color: #333;
        }
        .about-left p {
            line-height: 1.8;
            color: #555;
        }
        .about-right ul {
            padding: 0;
            list-style: none;
        }
        .about-right ul li {
            font-size: 16px;
            color: #444;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .about-right ul li i {
            margin-right: 10px;
            color: #007bff;
        }
        .about-btn {
            margin-top: 20px;
        }
        .about-btn a, .about-btn button {
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 25px;
            font-weight: 600;
        }
        .modal-content {
            border-radius: 10px;
        }
        .modal-body input, .modal-body textarea {
            width: 100%;
            border-radius: 6px;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }


        </style>
</head>
<body>
    <!--body-wraper-are-start-->
    <div id="home" class="wrapper event-details"><!-- We can rename "event-details" class to "training-details" if you prefer -->

        <!--slider header area are start-->
        <div id="home" class="header-slider-area">
            <!--header start-->
            <?php include_once('includes/header.php'); ?>
            <!-- header End-->
        </div>
        <!--slider header area are end-->

        <!--  breadcumb-area start-->
        <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
            <div class="container">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Training Details</li>
                </ol>
            </div>
        </div> 
        <!--  breadcumb-area end--> 

<?php
// Training Details
$tid = intval($_GET['trnid']);
$isactive = 1; // If you have an IsActive column in tbltraining
$sql = "SELECT 
            t.trainingName,
            t.trainingLocation,
            t.trainingStartDate,
            t.trainingEndDate,
            t.trainingDescription,
            t.trainingImage,
            tr.trainerName
        FROM tbltraining t
        LEFT JOIN tbltrainers tr ON tr.id = t.trainerId
        WHERE t.id = :tid AND t.IsActive = :isactive";
$query = $dbh->prepare($sql);
$query->bindParam(':isactive', $isactive, PDO::PARAM_STR);
$query->bindParam(':tid', $tid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
    foreach($results as $row)
    { 
?>           
        <!--about area are start-->
        <div class="about-area ptb100 fix" id="about-training">
            <div class="container">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="about-left">
                            <div class="about-top">
                                <h1 class="section-title" style="text-align:justify; line-height:42px; color:blue">
                                    <?php echo htmlentities($row->trainingName); ?> Details
                                </h1>
                                <div class="total-step">
                                    <div class="descp">
                                        <p><?php echo htmlentities($row->trainingDescription); ?></p>
                                    </div>
                    
                                </div>
                            </div>
                            <hr />
                            <h3>Trainer</h3>
                            <div class="total-step">
                                <div class="about-step">
                                    <h5 class="sub-title">
                                        <?php echo htmlentities($row->trainerName); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>  
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <p align="center">
                            <img src="admin/trainingimages/<?php echo htmlentities($row->trainingImage); ?>" 
                                 width="350" style="border:solid 1px #000">
                        </p>
                        <div class="about-right">
                            <ul>
                                <li>
                                    <i class="zmdi zmdi-pin"></i>
                                    <?php echo htmlentities($row->trainingLocation); ?>
                                </li>
                                <li>
                                    <i class="zmdi zmdi-calendar-note"></i>
                                    <?php echo htmlentities($row->trainingStartDate); ?> 
                                    To 
                                    <?php echo htmlentities($trainingEndDate = $row->trainingEndDate); ?>
                                </li>             
                            </ul>
<?php 
    $currentDate = date('Y-m-d');
    // If the training hasn't ended yet
    if($currentDate <= $trainingEndDate){
        // Check if user is logged in
        if(strlen($_SESSION['usrid']) == 0){
?>
                            <div class="about-btn">
                                <a href="signin.php" class="btn-def bnt-2">Book Now</a>
                            </div>
<?php 
        } else {
?>
                            <div class="about-btn"> 
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
                                    Book Now
                                </button>
                            </div> 
<?php 
        }
    } else {
?>
                            <div class="about-btn">
                                <a href="#" class="btn-def bnt-2">Training Expired</a>
                            </div>
<?php 
    }
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--about area are end-->

<?php 
    }
} else {
    echo '<h3 align="center" style="color:red; margin-top: 4%">No record found</h3>';
}
?>

<!-- Modal for booking -->
<div id="myModal" class="modal fade" role="dialog" style="margin-top:10%">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Book Training</h4>
      </div>
      <div class="modal-body">
        <form name="booktraining" method="post">
            <p>
                <input type="text" placeholder="Number of members" class="info" name="noofmembers" required="true">
            </p>
            <p>
                <textarea placeholder="User remark" class="info" name="userremark" required="true"></textarea>
            </p>
            <p>
                <button type="submit" class="btn btn-info btn-lg" name="book">Submit</button>
            </p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<?php include_once('includes/footer.php'); ?>
<!--footer area end-->            

</div>   
<!--body-wraper-are-end-->
		
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
