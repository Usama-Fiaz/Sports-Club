<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession']) == 0) {   
    header('location:logout.php');
} else { 
    if(isset($_POST['add'])) {
        $categoryName = $_POST['categoryName'];
        $categoryDescription = $_POST['categoryDescription'];
        $createdBy = $_SESSION['adminsession']; 
        $creationDate = date("Y-m-d H:i:s");
        $updationDate = date("Y-m-d H:i:s");

        // Insert query
        $sql = "INSERT INTO shopping_category (categoryName, categoryDescription, creationDate, updationDate, createdBy) 
                VALUES (:categoryName, :categoryDescription, :creationDate, :updationDate, :createdBy)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
        $query->bindParam(':categoryDescription', $categoryDescription, PDO::PARAM_STR);
        $query->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
        $query->bindParam(':updationDate', $updationDate, PDO::PARAM_STR);
        $query->bindParam(':createdBy', $createdBy, PDO::PARAM_INT);

        if($query->execute()) {
            $msg = "Category added successfully.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Add Shopping Category</title>
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
.succWrap{
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
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Shopping Category</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Category</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form role="form" method="post">
                                    <?php if($error){ ?><div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div><?php } 
                                    else if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div><?php } ?>

                                    <div class="form-group">
                                        <label>Category Name</label>
                                        <input class="form-control" type="text" name="categoryName" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="categoryDescription" required></textarea>
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary" name="add">Add Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </div> <!-- col-lg-12 -->
        </div> <!-- row -->
    </div> <!-- page-wrapper -->
</div> <!-- wrapper -->

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
