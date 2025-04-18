<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

// Fetch upcoming events
$isActive = 1;
$sql = "SELECT id, EventName, EventLocation, EventStartDate, EventEndDate, EventImage
        FROM tblevents
        WHERE IsActive = :isactive
        ORDER BY id DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':isactive', $isActive, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Events</title>
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

      .event-card {
    
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd;
        background: #fff;
        transition: box-shadow 0.3s, transform 0.3s;
      }
      .event-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-3px);
      }

     
      .event-img {
        width: 100%;
        height: 180px; 
        object-fit: cover;
        border-bottom: 1px solid #ddd;
        flex: 0 0 auto; 
      }

   
      .event-body {
        flex: 1 1 auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
      }
      .event-dates {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 5px;
      }
      .event-body h4 {
        margin-top: 0;
        font-weight: 600;
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
     
      .category-widget ul {
        list-style: none;
        padding-left: 0;
      }
      .category-widget ul li {
        margin-bottom: 5px;
      }
      .category-widget ul li a {
        color: #333;
        text-decoration: none;
      }
      .category-widget ul li a:hover {
        text-decoration: underline;
      }
    </style>
</head>
<body>
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
            <li class="active">All Events</li>
        </ol>
    </div>
</div>

<!-- ========== Main Content ========== -->
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
  <div class="row">
    <!-- Left Column: Events Listing -->
    <div class="col-md-9">
      <h1 class="section-title">Upcoming Events</h1>

      <!-- Make the row flex, so columns have equal height -->
      <div class="row-eq-height">
        <?php
        if ($query->rowCount() > 0) {
          foreach($results as $row) {
              // Fallback if EventImage is missing
              $imgPath = (!empty($row->EventImage))
                         ? "admin/eventimages/" . htmlentities($row->EventImage)
                         : "images/placeholder.png";
        ?>
        <div class="col-sm-6">
          <div class="event-card">
            <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlentities($row->EventName); ?>" class="event-img">
            <div class="event-body">
              <div class="event-dates">
                <?php echo htmlentities($row->EventStartDate); ?> - <?php echo htmlentities($row->EventEndDate); ?>
              </div>
              <h4><?php echo htmlentities($row->EventName); ?></h4>
              <!-- location & button on same line at bottom -->
              <div class="location-and-button">
                <p style="margin:0;"><strong>Location:</strong> <?php echo htmlentities($row->EventLocation); ?></p>
                <a href="event-details.php?evntid=<?php echo $row->id; ?>" class="btn-def bnt-2 small">
                  View Details
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
          }
        } else {
          echo "<div class='col-12'><p>No upcoming events found.</p></div>";
        }
        ?>
      </div><!-- /row-eq-height -->
    </div><!-- /col-md-9 -->

    <!-- Right Column: Sidebar -->
    <div class="col-md-1">
      <?php include_once('includes/sidebar.php'); ?>
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
