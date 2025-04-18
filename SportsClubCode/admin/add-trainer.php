<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Ensure $dbh is your PDO connection

if(strlen($_SESSION['adminsession']) == 0) {   
    header('location:logout.php');
    exit;
} else {
    if(isset($_POST['addTrainer'])) {
        $trainerName      = $_POST['trainerName'];
        $trainerEmail     = $_POST['trainerEmail'];
        $trainerContact   = $_POST['trainerContact'];
        $trainerSpecialty = $_POST['trainerSpecialty'];
        $trainerImage     = $_FILES["trainerImage"]["name"];
        
        // Allowed extensions
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif");
        $extension = substr($trainerImage, strlen($trainerImage)-4, strlen($trainerImage));
        if(!in_array($extension, $allowed_extensions)) {
            $error = "Invalid format. Only jpg / jpeg / png / gif allowed";
        } else {
            $imgnewfile = md5($trainerImage).$extension;
            move_uploaded_file($_FILES["trainerImage"]["tmp_name"], "trainerimages/" . $imgnewfile);
            
            $sql = "INSERT INTO tbltrainers (trainerName, trainerEmail, trainerContact, trainerSpecialty, trainerImage)
                    VALUES (:tName, :tEmail, :tContact, :tSpec, :tImage)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':tName',   $trainerName,      PDO::PARAM_STR);
            $query->bindParam(':tEmail',  $trainerEmail,     PDO::PARAM_STR);
            $query->bindParam(':tContact',$trainerContact,   PDO::PARAM_STR);
            $query->bindParam(':tSpec',   $trainerSpecialty, PDO::PARAM_STR);
            $query->bindParam(':tImage',  $imgnewfile,       PDO::PARAM_STR);
            $query->execute();
            
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId) {
                $msg = "Trainer added successfully.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Add Trainer</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .errorWrap { padding: 10px; background: #fff; border-left: 4px solid #dd3d36; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); }
        .succWrap  { padding: 10px; background: #fff; border-left: 4px solid #5cb85c;  box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/leftbar.php'); ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Trainer</h1>
            </div>
        </div>
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
        <?php } else if(isset($msg)) { ?>
            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">Add Trainer</div>
            <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Trainer Name</label>
                        <input type="text" name="trainerName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Trainer Email</label>
                        <input type="email" name="trainerEmail" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Trainer Contact</label>
                        <input type="text" name="trainerContact" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Trainer Specialty</label>
                        <input type="text" name="trainerSpecialty" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Trainer Image (jpg/jpeg/png/gif)</label>
                        <input type="file" name="trainerImage" required>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" name="addTrainer" class="btn btn-primary">Add Trainer</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
