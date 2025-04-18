<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

// Set active flag (adjust as needed)
$isActive = 1;

// Fetch active trainings and join with tbltrainers to get the trainer's name
$sql = "SELECT 
            t.id, 
            t.trainingName, 
            t.trainingLocation, 
            t.trainingStartDate, 
            t.trainingEndDate, 
            t.trainingImage, 
            t.IsActive,
            tr.trainerName
        FROM tbltraining t
        JOIN tbltrainers tr ON t.trainerId = tr.id
        WHERE t.IsActive = :isactive
        ORDER BY t.id DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':isactive', $isActive, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Trainings</title>
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

    <!-- Inline styling to enforce equal card heights via flexbox -->
    <style>
      .row-eq-height {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
      }
      .row-eq-height .col-sm-6 {
        display: flex;
        flex-direction: column;
        padding-left: 15px;
        padding-right: 15px;
        margin-bottom: 20px;
      }
      .training-card {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd;
        background: #fff;
        transition: box-shadow 0.3s, transform 0.3s;
      }
      .training-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-3px);
      }
      .training-img {
        width: 100%;
        height: 180px; 
        object-fit: cover;
        border-bottom: 1px solid #ddd;
        flex: 0 0 auto; 
      }
      .training-body {
        flex: 1 1 auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
      }
      .training-dates {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 5px;
      }
      .training-body h4 {
        margin-top: 0;
        font-weight: 600;
      }
      .trainer-name {
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 8px;
      }
      .location-and-button {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto; 
      }
      .btn-def.bnt-2.small {
        background-color: #000 !important; 
        color: #fff !important;
        border: none !important;
        text-transform: uppercase;
      }
    </style>
</head>
<body>
<!-- ========== Header Section ========== -->
<div id="home" class="header-slider-area">
    <?php include_once('includes/header.php'); ?>
</div>

<!-- ========== Breadcumb Section ========== -->
<div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active">All Trainings</li>
        </ol>
    </div>
</div>

<!-- ========== Main Content ========== -->
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
  <div class="row">
    <!-- Left Column: Training Listing -->
    <div class="col-md-9">
      <h1 class="section-title">Membership Privileges Training Sessions</h1>
      <div class="row-eq-height">
        <?php
        if ($query->rowCount() > 0) {
          foreach($results as $row) {
              // Fallback if trainingImage is missing
              $imgPath = (!empty($row->trainingImage))
                         ? "admin/trainingimages/" . htmlentities($row->trainingImage)
                         : "images/placeholder.png";
        ?>
        <div class="col-sm-6">
          <div class="training-card">
            <img src="<?php echo $imgPath; ?>" 
                 alt="<?php echo htmlentities($row->trainingName); ?>" 
                 class="training-img">
            <div class="training-body">
              <div class="training-dates">
                <?php echo htmlentities($row->trainingStartDate); ?> - <?php echo htmlentities($row->trainingEndDate); ?>
              </div>
              <h4><?php echo htmlentities($row->trainingName); ?></h4>
              <div class="trainer-name">
                <strong>Trainer:</strong> <?php echo htmlentities($row->trainerName); ?>
              </div>
              <div class="location-and-button">
                <p style="margin:0;">
                  <strong>Location:</strong> <?php echo htmlentities($row->trainingLocation); ?>
                </p>
                <!-- Link to training details page -->
                <a href="training-details.php?trnid=<?php echo $row->id; ?>" class="btn-def bnt-2 small">
                  View Details
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
          }
        } else {
          echo "<div class='col-12'><p>No upcoming trainings found.</p></div>";
        }
        ?>
      </div><!-- /row-eq-height -->
    </div><!-- /col-md-9 -->

    <!-- Right Column: Sidebar -->
    <div class="col-md-1">
      <?php include_once('includes/trainingsidebar.php'); ?>
    </div>
  </div><!-- /row -->
</div><!-- /container -->

<!-- ========== Footer ========== -->
<?php include_once('includes/footer.php'); ?>

<!-- ========== JS Files ========== -->
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
