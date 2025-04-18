<?php
session_start();
error_reporting(0);
include('includes/config.php');  // Ensure $dbh is your PDO connection

if (isset($_POST['registerTrainer'])) {
    $trainerName      = $_POST['trainerName']      ?? '';
    $trainerEmail     = $_POST['trainerEmail']     ?? '';
    $trainerContact   = $_POST['trainerContact']   ?? '';
    $trainerSpecialty = $_POST['trainerSpecialty'] ?? '';

    // Handle the trainer image upload
    $imageFile  = $_FILES["trainerImage"]["name"];
    $extension  = substr($imageFile, strrpos($imageFile, '.'));
    // Allowed file extensions
    $allowed_extensions = array(".jpg",".jpeg",".png",".gif");

    // Check if extension is valid
    if (!in_array(strtolower($extension), $allowed_extensions)) {
        $error = "Invalid format. Only jpg / jpeg / png / gif format allowed.";
    } else {
        // Generate a unique filename to avoid collisions
        $imageNewName = md5($imageFile.time()).$extension;
        // Move file to your desired folder (e.g., "trainerimages")
        move_uploaded_file($_FILES["trainerImage"]["tmp_name"], "trainerimages/".$imageNewName);

        try {
            // Insert into tbltrainers (adjust if your table/columns differ)
            $sql = "INSERT INTO tbltrainers (
                        trainerName, 
                        trainerEmail, 
                        trainerContact, 
                        trainerSpecialty, 
                        trainerImage
                    ) VALUES (
                        :trainerName, 
                        :trainerEmail, 
                        :trainerContact, 
                        :trainerSpecialty, 
                        :trainerImage
                    )";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':trainerName',      $trainerName,      PDO::PARAM_STR);
            $stmt->bindParam(':trainerEmail',     $trainerEmail,     PDO::PARAM_STR);
            $stmt->bindParam(':trainerContact',   $trainerContact,   PDO::PARAM_STR);
            $stmt->bindParam(':trainerSpecialty', $trainerSpecialty, PDO::PARAM_STR);
            $stmt->bindParam(':trainerImage',     $imageNewName,     PDO::PARAM_STR);

            $stmt->execute();
            $lastId = $dbh->lastInsertId();

            if ($lastId) {
                $msg = "Trainer registered successfully!";
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
    <title>Register Trainer</title>
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
      .register-trainer-container {
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

    <!-- Breadcrumb -->
    <div class="breadcumb-area bg-overlay" style="background: #333; background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Register Trainer</li>
            </ol>
        </div>
    </div>

    <!-- Main Content -->
    <div class="register-trainer-container container">
        <h1 class="page-title">Register Trainer</h1>

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
            <!-- Trainer Name -->
            <label for="trainerName">Trainer Name</label>
            <input type="text" name="trainerName" id="trainerName" class="form-control" required>

            <!-- Trainer Email -->
            <label for="trainerEmail">Trainer Email</label>
            <input type="email" name="trainerEmail" id="trainerEmail" class="form-control" required>

            <!-- Trainer Contact -->
            <label for="trainerContact">Trainer Contact</label>
            <input type="text" name="trainerContact" id="trainerContact" class="form-control" required>

            <!-- Trainer Specialty -->
            <label for="trainerSpecialty">Trainer Specialty</label>
            <input type="text" name="trainerSpecialty" id="trainerSpecialty" class="form-control" required>

            <!-- Trainer Image -->
            <label for="trainerImage">Trainer Image</label>
            <input type="file" name="trainerImage" id="trainerImage" class="form-control" required>

            <button type="submit" name="registerTrainer" class="btn btn-primary submit-btn" style="margin-top:20px;">
                Register Trainer
            </button>
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
