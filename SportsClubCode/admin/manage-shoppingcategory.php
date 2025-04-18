<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession'])==0) {   
    header('location:logout.php');
} else {    
    // Code for Category deletion 
    if(isset($_GET['del'])) {
        $id=$_GET['del'];
        $sql = "DELETE FROM shopping_category WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['delmsg'] = "Category deleted successfully.";
    } 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>SportsSync | Manage Shopping Categories</title>

    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

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
                <h1 class="page-header">Manage Shopping Categories</h1>
            </div>
        </div>

        <?php if($_SESSION['delmsg']!="") { ?>
            <div class="errorWrap">
                <strong>Success :</strong> 
                <?php echo htmlentities($_SESSION['delmsg']); ?>
                <?php echo htmlentities($_SESSION['delmsg'] = ""); ?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Shopping Categories</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Creation Date</th>
                                        <th>Last Updated</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM shopping_category";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;

                                    if($query->rowCount() > 0) {
                                        foreach($results as $row) { 
                                    ?>
                                    <tr>
                                        <td><?php echo htmlentities($cnt); ?></td>
                                        <td><?php echo htmlentities($row->categoryName); ?></td>
                                        <td><?php echo htmlentities($row->categoryDescription); ?></td>
                                        <td><?php echo htmlentities($row->creationDate); ?></td>
                                        <td><?php echo htmlentities($row->updationDate); ?></td>
                                        <td><?php echo htmlentities($row->createdBy); ?></td>
                                        <td>
                                            <a href="edit-shopping-category.php?catid=<?php echo htmlentities($row->id);?>">
                                                <button type="button" class="btn btn-info btn-circle"><i class="fa fa-edit"></i></button>
                                            </a>
                                            <a href="manage-shopping-category.php?del=<?php echo htmlentities($row->id);?>" onclick="return confirm('Are you sure you want to delete this category?');">
                                                <button type="button" class="btn btn-danger btn-circle" style="margin-left:4px;">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $cnt++; }} ?>
                                </tbody>
                            </table>
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
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

</body>
</html>
<?php } ?>
