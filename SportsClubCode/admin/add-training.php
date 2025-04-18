<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else { 
    if(isset($_POST['add']))
    {
        // Posted Values
        $trainerid           = $_POST['trainer'];           // Selected trainer from tbltrainers
        $trainingname        = $_POST['trainingname'];
        $traininglocation    = $_POST['traininglocation'];
        $trainingstartdate   = $_POST['trainingstartdate'];
        $trainingenddate     = $_POST['trainingenddate'];
        $trainingdescription = $_POST['trainingdescription']; // Training description from form
        $categoryid          = $_POST['trainingcategory'];  // Selected training category from tbltrainingcategory
        $entimage            = $_FILES["trainingimage"]["name"];
        $status              = 1;
        
        // Get the image extension
        $extension = substr($entimage, strlen($entimage)-4);
        // Allowed extensions
        $allowed_extensions = array(".jpg",".jpeg",".png",".gif");
        
        // Validate allowed extensions
        if(!in_array(strtolower($extension), $allowed_extensions))
        {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
        }
        else
        {
            $trainingimage = md5($entimage).$extension;
            // Move the uploaded image into directory "trainingimages"
            move_uploaded_file($_FILES["trainingimage"]["tmp_name"], "trainingimages/".$trainingimage);
            
            // Query for inserting training data into tbltraining including CategoryId and trainingDescription
            $sql = "INSERT INTO tbltraining(
                        trainerId,
                        trainingName,
                        trainingLocation,
                        trainingStartDate,
                        trainingEndDate,
                        trainingDescription,
                        trainingImage,
                        CategoryId,
                        IsActive
                    ) VALUES (
                        :trainerid,
                        :trainingname,
                        :traininglocation,
                        :trainingstartdate,
                        :trainingenddate,
                        :trainingdescription,
                        :trainingimage,
                        :categoryid,
                        :status
                    )";
            $query = $dbh->prepare($sql);
            $query->bindParam(':trainerid', $trainerid, PDO::PARAM_STR);
            $query->bindParam(':trainingname', $trainingname, PDO::PARAM_STR);
            $query->bindParam(':traininglocation', $traininglocation, PDO::PARAM_STR);
            $query->bindParam(':trainingstartdate', $trainingstartdate, PDO::PARAM_STR);
            $query->bindParam(':trainingenddate', $trainingenddate, PDO::PARAM_STR);
            $query->bindParam(':trainingdescription', $trainingdescription, PDO::PARAM_STR);
            $query->bindParam(':trainingimage', $trainingimage, PDO::PARAM_STR);
            $query->bindParam(':categoryid', $categoryid, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            
            if($lastInsertId)
            {
                echo '<script>alert("Training created successfully")</script>';
                echo "<script>window.location.href='manage-training.php'</script>";  
            }
            else 
            {
                echo '<script>alert("Something went wrong. Please try again")</script>';   
            }
        }
    }    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsSync | Add Training</title>
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
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
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
        <!-- Leftbar -->
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Training</h1>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Add Training
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Success / Error Message -->
                                <?php if($error){ ?>
                                    <div class="errorWrap"><strong>ERROR</strong> : <?php echo htmlentities($error); ?></div>
                                <?php } else if($msg){ ?>
                                    <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <!-- Trainer Selection -->
                                    <div class="form-group">
                                        <label>Trainer</label>
                                        <select class="form-control" name="trainer" autocomplete="off" required>
                                            <option>Select</option>
                                            <?php
                                            $sql = "SELECT id, trainerName FROM tbltrainers";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0)
                                            {
                                                foreach($results as $row)
                                                { ?>  
                                                    <option value="<?php echo htmlentities($row->id);?>">
                                                        <?php echo htmlentities($row->trainerName);?>
                                                    </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Training Category Selection -->
                                    <div class="form-group">
                                        <label>Training Category</label>
                                        <select class="form-control" name="trainingcategory" autocomplete="off" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            $sql = "SELECT id, CategoryName FROM tbltrainingcategory WHERE isActive = 1 ORDER BY CategoryName ASC";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0)
                                            {
                                                foreach($results as $cat)
                                                {
                                                    echo "<option value='".htmlentities($cat->id)."'>".htmlentities($cat->CategoryName)."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Training Name -->
                                    <div class="form-group">
                                        <label>Training Name</label>
                                        <input class="form-control" type="text" name="trainingname" autocomplete="off" required autofocus>
                                    </div>

                                    <!-- Training Location -->
                                    <div class="form-group">
                                        <label>Training Location</label>
                                        <input class="form-control" type="text" name="traininglocation" autocomplete="off" required autofocus>
                                    </div>

                                    <!-- Training Start Date -->
                                    <div class="form-group">
                                        <label>Training Start Date</label>
                                        <input class="form-control" type="date" name="trainingstartdate" autocomplete="off" required autofocus />
                                    </div>

                                    <!-- Training End Date -->
                                    <div class="form-group">
                                        <label>Training End Date</label>
                                        <input class="form-control" type="date" name="trainingenddate" autocomplete="off" required autofocus />
                                    </div>

                                    <!-- Training Description -->
                                    <div class="form-group">
                                        <label>Training Description</label>
                                        <textarea class="form-control" name="trainingdescription" rows="5" autocomplete="off" required autofocus></textarea>
                                    </div>

                                    <!-- Training Featured Image -->
                                    <div class="form-group">
                                        <label>Training Featured Image</label>
                                        <input class="form-control" type="file" name="trainingimage" autocomplete="off" required autofocus />
                                    </div>

                                    <!-- Button -->
                                    <button type="submit" class="btn btn-default" name="add">Add Training</button>
                                </form>
                            </div>
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
