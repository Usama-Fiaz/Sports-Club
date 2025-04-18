<?php
session_start();
include('includes/config.php'); // Ensure $dbh is your PDO connection
error_reporting(0);

// Fetch all trainers from tbltrainers
$sql = "SELECT id, trainerName, trainerEmail, trainerContact, trainerSpecialty, trainerImage FROM tbltrainers ORDER BY id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$trainers = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsSync | Our Trainers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Files -->
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
        /* Styling for trainer cards */
        .trainer-card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            background: #fff;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            padding: 15px;
        }
        .trainer-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        .trainer-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
        .trainer-details {
            padding-top: 10px;
        }
        .trainer-details h4 {
            margin-top: 0;
            font-weight: 600;
        }
        .trainer-details p {
            margin-bottom: 5px;
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcumb -->
    <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Our Trainers</li>
            </ol>
        </div>
    </div>

    <!-- Main Content: Trainers Listing -->
    <div class="container ptb100">
        <h1 class="section-title text-center">Our Trainers</h1>
        <div class="row">
            <?php if($query->rowCount() > 0): ?>
                <?php foreach($trainers as $trainer): 
                    // Use a placeholder if no trainerImage is provided
                    $imgPath = !empty($trainer->trainerImage) 
                        ? "admin/trainerimages/" . htmlentities($trainer->trainerImage) 
                        : "images/placeholder.png";
                ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="trainer-card">
                            <img src="<?php echo $imgPath; ?>" alt="Trainer Image" class="trainer-img">
                            <div class="trainer-details">
                                <h4><?php echo htmlentities($trainer->trainerName); ?></h4>
                                <p><strong>Specialty:</strong> <?php echo htmlentities($trainer->trainerSpecialty); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlentities($trainer->trainerEmail); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlentities($trainer->trainerContact); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No trainers found.</p>
                </div>
            <?php endif; ?>
        </div>
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
