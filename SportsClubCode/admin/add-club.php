<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Ensure this creates your PDO connection in $dbh

// Check if admin is logged in
if(strlen($_SESSION['adminsession']) == 0) {   
    header('location:logout.php');
    exit;
} else {

    // Handle form submission
    if(isset($_POST['add']))
    {
        // Posted values
        $clubName        = $_POST['clubName'];
        $clubDescription = $_POST['clubDescription'];
        $clubEmail       = $_POST['clubEmail'];
        $clubContact     = $_POST['clubContact'];
        $clubAddress     = $_POST['clubAddress'];
        $clubLogo        = $_FILES["clubLogo"]["name"];

        // Allowed file extensions
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif");

        // Extract extension from uploaded file name
        // (Here we assume the last 4 chars might be .jpg, .png, etc.
        //  If your file name has uppercase or longer extension, adjust accordingly)
        $extension = substr($clubLogo, strrpos($clubLogo, '.'));

        // Validate extension
        if(!in_array(strtolower($extension), $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
        } else {
            // Generate a unique file name (md5 hash + extension)
            $imgnewfile = md5($clubLogo).$extension;

            // Move the file to "clubimages" directory (create if not existing)
            move_uploaded_file($_FILES["clubLogo"]["tmp_name"], "clubimages/" . $imgnewfile);

            // Insert record into tblclubs
            // Note we do NOT fill updationDate or IsActive if they default in DB
            $sql = "INSERT INTO tblclub 
                    (clubName, clubDescription, clubEmail, clubContact, clubAddress, clubLogo, creationDate)
                    VALUES 
                    (:cName, :cDesc, :cEmail, :cContact, :cAddr, :cLogo, NOW())";
            $query = $dbh->prepare($sql);
            $query->bindParam(':cName',   $clubName,        PDO::PARAM_STR);
            $query->bindParam(':cDesc',   $clubDescription, PDO::PARAM_STR);
            $query->bindParam(':cEmail',  $clubEmail,       PDO::PARAM_STR);
            $query->bindParam(':cContact',$clubContact,     PDO::PARAM_STR);
            $query->bindParam(':cAddr',   $clubAddress,     PDO::PARAM_STR);
            $query->bindParam(':cLogo',   $imgnewfile,      PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId) {
                $msg = "Club created successfully.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Add Club</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap{
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <!-- Header -->
        <?php include_once('includes/header.php'); ?>
        <!-- Left Sidebar -->
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Club</h1>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Add Club
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">

                                <!-- Success / Error Message -->
                                <?php if($error){ ?>
                                    <div class="errorWrap">
                                        <strong>ERROR</strong> : <?php echo htmlentities($error); ?>
                                    </div>
                                <?php } else if($msg){ ?>
                                    <div class="succWrap">
                                        <strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php } ?>

                                <!-- Form to Add Club -->
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <!-- Club Name -->
                                    <div class="form-group">
                                        <label>Club Name</label>
                                        <input class="form-control" type="text" name="clubName" required autofocus>
                                    </div>

                                    <!-- Club Description -->
                                    <div class="form-group">
                                        <label>Club Description</label>
                                        <textarea class="form-control" name="clubDescription" rows="4" required></textarea>
                                    </div>

                                    <!-- Club Email -->
                                    <div class="form-group">
                                        <label>Club Email</label>
                                        <input class="form-control" type="email" name="clubEmail" required>
                                    </div>

                                    <!-- Club Contact -->
                                    <div class="form-group">
                                        <label>Club Contact</label>
                                        <input class="form-control" type="text" name="clubContact" required>
                                    </div>

                                    <!-- Club Address -->
                                    <div class="form-group">
                                        <label>Club Address</label>
                                        <textarea class="form-control" name="clubAddress" rows="2" required></textarea>
                                    </div>

                                    <!-- Club Logo -->
                                    <div class="form-group">
                                        <label>Club Logo</label>
                                        <input type="file" name="clubLogo" required />
                                    </div>

                                    <!-- Submit Button -->   
                                    <div class="form-group" align="center">
                                        <button type="submit" class="btn btn-primary" name="add">Add</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.col-lg-6 -->
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>

</body>
</html>
<?php } ?>
