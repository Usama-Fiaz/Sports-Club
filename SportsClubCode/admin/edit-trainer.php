<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession']) == 0) {
    header('location:logout.php');
    exit;
} else {
    $trainerId = intval($_GET['trainerid']);
    $sql = "SELECT * FROM tbltrainers WHERE id = :tid LIMIT 1";
    $query = $dbh->prepare($sql);
    $query->bindParam(':tid', $trainerId, PDO::PARAM_INT);
    $query->execute();
    $trainer = $query->fetch(PDO::FETCH_OBJ);
    if(!$trainer) {
        header("Location: manage-trainers.php");
        exit;
    }

    if(isset($_POST['updateTrainer'])) {
        $trainerName = $_POST['trainerName'];
        $trainerEmail = $_POST['trainerEmail'];
        $trainerContact = $_POST['trainerContact'];
        $trainerSpecialty = $_POST['trainerSpecialty'];

        if(!empty($_FILES["trainerImage"]["name"])) {
            $trainerImage = $_FILES["trainerImage"]["name"];
            $allowed_extensions = array(".jpg",".jpeg",".png",".gif");
            $extension = substr($trainerImage, strlen($trainerImage)-4, strlen($trainerImage));
            if(!in_array($extension, $allowed_extensions)) {
                $error = "Invalid format. Only jpg/jpeg/png/gif allowed";
            } else {
                $imgnewfile = md5($trainerImage).$extension;
                move_uploaded_file($_FILES["trainerImage"]["tmp_name"], "trainerimages/" . $imgnewfile);

                $sqlUpd = "UPDATE tbltrainers 
                           SET trainerName = :tName,
                               trainerEmail = :tEmail,
                               trainerContact = :tContact,
                               trainerSpecialty = :tSpec,
                               trainerImage = :tImage
                           WHERE id = :tid
                           LIMIT 1";
                $stmt = $dbh->prepare($sqlUpd);
                $stmt->bindParam(':tName', $trainerName, PDO::PARAM_STR);
                $stmt->bindParam(':tEmail', $trainerEmail, PDO::PARAM_STR);
                $stmt->bindParam(':tContact', $trainerContact, PDO::PARAM_STR);
                $stmt->bindParam(':tSpec', $trainerSpecialty, PDO::PARAM_STR);
                $stmt->bindParam(':tImage', $imgnewfile, PDO::PARAM_STR);
                $stmt->bindParam(':tid', $trainerId, PDO::PARAM_INT);
                $stmt->execute();
                $msg = "Trainer updated successfully with new image!";
            }
        } else {
            $sqlUpd = "UPDATE tbltrainers
                       SET trainerName = :tName,
                           trainerEmail = :tEmail,
                           trainerContact = :tContact,
                           trainerSpecialty = :tSpec
                       WHERE id = :tid
                       LIMIT 1";
            $stmt = $dbh->prepare($sqlUpd);
            $stmt->bindParam(':tName', $trainerName, PDO::PARAM_STR);
            $stmt->bindParam(':tEmail', $trainerEmail, PDO::PARAM_STR);
            $stmt->bindParam(':tContact', $trainerContact, PDO::PARAM_STR);
            $stmt->bindParam(':tSpec', $trainerSpecialty, PDO::PARAM_STR);
            $stmt->bindParam(':tid', $trainerId, PDO::PARAM_INT);
            $stmt->execute();
            $msg = "Trainer updated successfully!";
        }
        header("Location: edit-trainer.php?trainerid=" . $trainerId);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Trainer</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
    .errorWrap { padding: 10px; background: #fff; border-left: 4px solid #dd3d36; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); }
    .succWrap  { padding: 10px; background: #fff; border-left: 4px solid #5cb85c;  box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); }
    .trainer-img { width: 120px; border: 1px solid #ddd; }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/leftbar.php'); ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit Trainer</h1>
            </div>
        </div>
        <?php if(isset($error)) { ?>
            <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
        <?php } else if(isset($msg)) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">Edit Trainer Details</div>
            <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Trainer Name</label>
                        <input type="text" name="trainerName" class="form-control" value="<?php echo htmlentities($trainer->trainerName); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Trainer Email</label>
                        <input type="email" name="trainerEmail" class="form-control" value="<?php echo htmlentities($trainer->trainerEmail); ?>">
                    </div>
                    <div class="form-group">
                        <label>Trainer Contact</label>
                        <input type="text" name="trainerContact" class="form-control" value="<?php echo htmlentities($trainer->trainerContact); ?>">
                    </div>
                    <div class="form-group">
                        <label>Trainer Specialty</label>
                        <input type="text" name="trainerSpecialty" class="form-control" value="<?php echo htmlentities($trainer->trainerSpecialty); ?>">
                    </div>
                    <div class="form-group">
                        <label>Current Image</label><br>
                        <?php if(!empty($trainer->trainerImage)): ?>
                            <img src="trainerimages/<?php echo htmlentities($trainer->trainerImage); ?>" class="trainer-img" alt="Trainer Image">
                        <?php else: ?>
                            <p>No image uploaded</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Upload New Image (optional)</label>
                        <input type="file" name="trainerImage">
                    </div>
                    <button type="submit" name="updateTrainer" class="btn btn-primary">Update Trainer</button>
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
