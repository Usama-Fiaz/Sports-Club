<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else { 
    if(isset($_POST['update']))
    {
        // Getting Values
        $trainingid = intval($_GET['tid']);    
        // Posted Values
        $trainerid           = $_POST['trainer'];
        $trainingname        = $_POST['trainingname'];
        $traininglocation    = $_POST['traininglocation'];
        $trainingstartdate   = $_POST['trainingstartdate'];
        $trainingenddate     = $_POST['trainingenddate'];
        $trainingdescription = $_POST['trainingdescription'];
        $categoryid          = $_POST['trainingcategory'];
        
        // Query for updating training data into database, including trainingDescription and CategoryId
        $sql = "UPDATE tbltraining 
                SET trainerId = :trainerid,
                    trainingName = :trainingname,
                    trainingLocation = :traininglocation,
                    trainingStartDate = :trainingstartdate,
                    trainingEndDate = :trainingenddate,
                    trainingDescription = :trainingdescription,
                    CategoryId = :categoryid
                WHERE id = :tid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':trainerid', $trainerid, PDO::PARAM_STR);
        $query->bindParam(':trainingname', $trainingname, PDO::PARAM_STR);
        $query->bindParam(':traininglocation', $traininglocation, PDO::PARAM_STR);
        $query->bindParam(':trainingstartdate', $trainingstartdate, PDO::PARAM_STR);
        $query->bindParam(':trainingenddate', $trainingenddate, PDO::PARAM_STR);
        $query->bindParam(':trainingdescription', $trainingdescription, PDO::PARAM_STR);
        $query->bindParam(':categoryid', $categoryid, PDO::PARAM_STR);
        $query->bindParam(':tid', $trainingid, PDO::PARAM_STR);
        $query->execute();
        
        echo "<script>alert('Success : Training details updated successfully');</script>";
        echo "<script>window.location.href='manage-training.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Training</title>
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
        <!-- / Header -->
        <?php include_once('includes/header.php'); ?>
        <!-- / Leftbar -->
        <?php include_once('includes/leftbar.php'); ?>
    </nav>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"> Edit Training</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       Edit Training
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form role="form" method="post" enctype="multipart/form-data">
                                <?php
                                $trainingid = intval($_GET['tid']);
                                // Updated SELECT query to include CategoryId
                                $sql = "SELECT 
                                            t.id as tid,
                                            t.trainingName,
                                            t.trainingLocation,
                                            t.trainingStartDate,
                                            t.trainingEndDate,
                                            t.trainingImage,
                                            t.trainingDescription,
                                            t.trainerId,
                                            t.CategoryId,
                                            tr.trainerName
                                        FROM tbltraining t 
                                        LEFT JOIN tbltrainers tr ON tr.id = t.trainerId
                                        WHERE t.id = :tid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':tid', $trainingid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0)
                                {
                                    foreach($results as $result)
                                    { 
                                ?>
                                    <!-- Trainer Selection -->
                                    <div class="form-group">
                                        <label>Trainer</label>
                                        <select class="form-control" name="trainer" required>
                                            <option value="<?php echo htmlentities($result->trainerId);?>">
                                                <?php echo htmlentities($currentTrainer = $result->trainerName); ?>
                                            </option>
                                            <?php
                                            $sql = "SELECT id, trainerName FROM tbltrainers";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $trainers = $query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0)
                                            {
                                                foreach($trainers as $row)
                                                { 
                                                    if($currentTrainer == $row->trainerName)
                                                    {
                                                        continue;
                                                    }
                                                    else
                                                    {
                                            ?>  
                                            <option value="<?php echo htmlentities($row->id);?>">
                                                <?php echo htmlentities($row->trainerName); ?>
                                            </option>
                                            <?php 
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Training Name -->
                                    <div class="form-group">
                                        <label>Training Name</label>
                                        <input class="form-control" type="text" name="trainingname" value="<?php echo htmlentities($result->trainingName);?>" required>
                                    </div>

                                    <!-- Training Location -->
                                    <div class="form-group">
                                        <label>Training Location</label>
                                        <input class="form-control" type="text" name="traininglocation" value="<?php echo htmlentities($result->trainingLocation);?>" required>
                                    </div>

                                    <!-- Training Start Date -->
                                    <div class="form-group">
                                        <label>Training Start Date</label>
                                        <input class="form-control" type="date" name="trainingstartdate" value="<?php echo htmlentities($result->trainingStartDate);?>" required>
                                    </div>

                                    <!-- Training End Date -->
                                    <div class="form-group">
                                        <label>Training End Date</label>
                                        <input class="form-control" type="date" name="trainingenddate" value="<?php echo htmlentities($result->trainingEndDate);?>" required>
                                    </div>

                                    <!-- Training Description -->
                                    <div class="form-group">
                                        <label>Training Description</label>
                                        <textarea class="form-control" name="trainingdescription" rows="5" required><?php echo htmlentities($result->trainingDescription);?></textarea>
                                    </div>
                                    
                                    <!-- Training Category Selection -->
                                    <div class="form-group">
                                        <label>Training Category</label>
                                        <select class="form-control" name="trainingcategory" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            // Fetch active categories from tbltrainingcategory
                                            $sql = "SELECT id, CategoryName FROM tbltrainingcategory WHERE isActive = 1 ORDER BY CategoryName ASC";
                                            $catQuery = $dbh->prepare($sql);
                                            $catQuery->execute();
                                            $categories = $catQuery->fetchAll(PDO::FETCH_OBJ);
                                            if($catQuery->rowCount() > 0)
                                            {
                                                foreach($categories as $cat)
                                                {
                                                    $selected = ($cat->id == $result->CategoryId) ? "selected" : "";
                                                    echo "<option value='".htmlentities($cat->id)."' $selected>" . htmlentities($cat->CategoryName) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Training Featured Image -->
                                    <div class="form-group">
                                        <label>Training Featured Image:</label>
                                        <img src="trainingimages/<?php echo htmlentities($result->trainingImage);?>" style="border:solid #000 1px" width="300">
                                        <a href="change-training-image.php?trnid=<?php echo htmlentities($result->tid);?>"> Change Training Image </a>
                                    </div>
                                <?php 
                                    }
                                } 
                                ?>
                                    <!-- Button -->  
                                    <div class="form-group" align="center">                     
                                        <button type="submit" class="btn btn-primary" name="update">Update Training</button>
                                    </div>
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
