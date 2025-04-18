<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession'])==0) {   
    header('location:logout.php');
} else { 
    if(isset($_POST['update'])) {
        $catName = $_POST['categoryName'];
        $description = $_POST['categoryDescription'];
        $cid = intval($_GET['catid']);

        $sql = "UPDATE shopping_category SET categoryName=:catName, categoryDescription=:description WHERE id=:cid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
        $query->bindParam(':catName', $catName, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->execute();

        $msg = "Shopping category updated successfully.";
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Shopping Category</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .errorWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
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
            <?php include_once('includes/header.php');?>
            <?php include_once('includes/leftbar.php');?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Shopping Category</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Category Details
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div><?php } ?>

                                        <?php
                                        $cid=intval($_GET['catid']);
                                        $sql = "SELECT id, categoryName, categoryDescription, creationDate FROM shopping_category WHERE id=:cid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);

                                        if($query->rowCount() > 0) {
                                            foreach($results as $row) { ?>
                                                <p><strong>Created on: </strong><?php echo htmlentities($row->creationDate); ?></p>

                                                <!-- Category Name -->
                                                <div class="form-group">
                                                    <label>Category Name</label>
                                                    <input class="form-control" type="text" name="categoryName" value="<?php echo htmlentities($row->categoryName); ?>" required>
                                                </div>

                                                <!-- Description -->
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control" name="categoryDescription" required><?php echo htmlentities($row->categoryDescription); ?></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-group" align="center">
                                                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                                                </div>
                                            <?php } 
                                        } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
