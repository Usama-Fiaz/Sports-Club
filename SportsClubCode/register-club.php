<?php
session_start();
error_reporting(0);
include('includes/config.php');  // Make sure $dbh is your PDO connection

// If the form is submitted
if (isset($_POST['registerClub'])) {
    $clubName        = $_POST['clubName']        ?? '';
    $clubDescription = $_POST['clubDescription'] ?? '';
    $clubEmail       = $_POST['clubEmail']       ?? '';
    $clubContact     = $_POST['clubContact']     ?? '';
    $clubAddress     = $_POST['clubAddress']     ?? '';

    // Handle the club logo upload (similar to sponsor logic)
    $logoFile  = $_FILES["clubLogo"]["name"];
    $extension = substr($logoFile, strrpos($logoFile, '.'));
    // Allowed file extensions
    $allowed_extensions = array(".jpg",".jpeg",".png",".gif");

    // Check if extension is valid
    if (!in_array(strtolower($extension), $allowed_extensions)) {
        $error = "Invalid format. Only jpg / jpeg / png / gif format allowed.";
    } else {
        // Generate a unique filename to avoid collisions
        $logoNewName = md5($logoFile.time()).$extension;
        // Move file to your desired folder (e.g., "clublogos")
        move_uploaded_file($_FILES["clubLogo"]["tmp_name"], "clublogos/".$logoNewName);

        try {
            // Insert into tblclubs
            // Adjust columns to match your actual table schema
            $sql = "INSERT INTO tblclub (
                        clubName, 
                        clubDescription, 
                        clubEmail, 
                        clubContact, 
                        clubAddress, 
                        clubLogo, 
                        creationDate, 
                        IsActive
                    ) VALUES (
                        :clubName, 
                        :clubDescription, 
                        :clubEmail, 
                        :clubContact, 
                        :clubAddress, 
                        :clubLogo, 
                        NOW(), 
                        1
                    )";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':clubName',        $clubName,        PDO::PARAM_STR);
            $stmt->bindParam(':clubDescription', $clubDescription, PDO::PARAM_STR);
            $stmt->bindParam(':clubEmail',       $clubEmail,       PDO::PARAM_STR);
            $stmt->bindParam(':clubContact',     $clubContact,     PDO::PARAM_STR);
            $stmt->bindParam(':clubAddress',     $clubAddress,     PDO::PARAM_STR);
            $stmt->bindParam(':clubLogo',        $logoNewName,     PDO::PARAM_STR);

            $stmt->execute();
            $lastId = $dbh->lastInsertId();

            if ($lastId) {
                $msg = "Club registered successfully!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Your Club</title>
    <!-- Include your CSS (Bootstrap etc.) -->
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
      /* Add some top margin to avoid header overlap */
      .register-club-container {
        margin-top: 80px; /* Adjust if your header is fixed */
        margin-bottom: 50px;
      }
      .page-title {
        font-size: 2rem;
        margin-bottom: 30px;
        font-weight: 600;
      }
      .form-control {
        margin-bottom: 15px;
      }
      .submit-btn {
        text-transform: uppercase;
      }
      /* Display success/error messages similarly to your other pages */
      .errorWrap {
        padding: 10px;
        background: #fff;
        border-left: 4px solid #dd3d36;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        margin-bottom: 20px;
      }
      .succWrap {
        padding: 10px;
        background: #fff;
        border-left: 4px solid #5cb85c;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        margin-bottom: 20px;
      }
    </style>
</head>
<body>
    <!-- Header -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- Breadcumb -->
    <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Register Your Club</li>
            </ol>
        </div>
    </div>

    <!-- Main Content -->
    <div class="register-club-container container">
        <h1 class="page-title">Register Your Club</h1>

        <!-- Display error/success messages -->
        <?php if(!empty($error)): ?>
          <div class="errorWrap">
            <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
          </div>
        <?php elseif(!empty($msg)): ?>
          <div class="succWrap">
            <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
          </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- Club Name -->
            <label for="clubName">Club Name</label>
            <input type="text" name="clubName" id="clubName" class="form-control" required>

            <!-- Club Description -->
            <label for="clubDescription">Club Description</label>
            <textarea name="clubDescription" id="clubDescription" rows="4" class="form-control" required></textarea>

            <!-- Club Email -->
            <label for="clubEmail">Club Email</label>
            <input type="email" name="clubEmail" id="clubEmail" class="form-control" required>

            <!-- Club Contact -->
            <label for="clubContact">Club Contact</label>
            <input type="text" name="clubContact" id="clubContact" class="form-control" required>

            <!-- Club Address -->
            <label for="clubAddress">Club Address</label>
            <textarea name="clubAddress" id="clubAddress" rows="3" class="form-control" required></textarea>

            <!-- Club Logo -->
            <label for="clubLogo">Club Logo</label>
            <input type="file" name="clubLogo" id="clubLogo" class="form-control" required>

            <!-- Submit Button -->
            <button type="submit" name="registerClub" class="btn btn-primary submit-btn">Register Club</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- Scripts -->
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
