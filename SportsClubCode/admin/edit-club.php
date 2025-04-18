<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Ensure $dbh is your PDO connection

// Check if admin is logged in; if not, redirect to logout
if(strlen($_SESSION['adminsession']) == 0) {   
    header('location:logout.php');
    exit;
} else { 
    // Retrieve the club id from GET
    $clubId = intval($_GET['clubid']);
    
    // Fetch existing club details from tblclub
    $sql = "SELECT * FROM tblclub WHERE id = :cid LIMIT 1";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cid', $clubId, PDO::PARAM_INT);
    $query->execute();
    $club = $query->fetch(PDO::FETCH_OBJ);
    
    // If form is submitted, update the club details
    if (isset($_POST['updateClub'])) {
        $clubName = $_POST['clubName'];
        $clubDescription = $_POST['clubDescription'];
        
        $sql = "UPDATE tblclub
                SET clubName = :clubName,
                    clubDescription = :clubDescription,
                    updationDate = NOW()
                WHERE id = :cid
                LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->bindParam(':clubName', $clubName, PDO::PARAM_STR);
        $query->bindParam(':clubDescription', $clubDescription, PDO::PARAM_STR);
        $query->bindParam(':cid', $clubId, PDO::PARAM_INT);
        $query->execute();
        
        $_SESSION['msg'] = "Club updated successfully";
        header("Location: manage-clubs.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Club</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
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
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>
    <!-- /Navigation -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit Club</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Club Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Display success/error messages if needed -->
                                <?php if(isset($error)) { ?>
                                    <div class="errorWrap">
                                        <strong>ERROR:</strong> <?php echo htmlentities($error); ?>
                                    </div>
                                <?php } else if(isset($msg)) { ?>
                                    <div class="succWrap">
                                        <strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php } ?>
                                
                                <form role="form" method="post">
                                    <!-- Club Name -->
                                    <div class="form-group">
                                        <label>Club Name</label>
                                        <input class="form-control" type="text" name="clubName" value="<?php echo htmlentities($club->clubName); ?>" required>
                                    </div>
                                    <!-- Club Description -->
                                    <div class="form-group">
                                        <label>Club Description</label>
                                        <textarea class="form-control" name="clubDescription" rows="5" required><?php echo htmlentities($club->clubDescription); ?></textarea>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary" name="updateClub">Update Club</button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
