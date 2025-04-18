<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Must create your PDO connection in $dbh

if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else{    

// Code for club deletion 
if(isset($_GET['del']))
{
    $id = intval($_GET['del']);
    // Make sure table name is correct: 'tblclub' or 'tblclubs'
    $sql = "DELETE FROM tblclub WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $_SESSION['delmsg'] = "Club deleted";
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Manage Clubs</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

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
        <?php include_once('includes/header.php');?>
        <!-- Leftbar -->
        <?php include_once('includes/leftbar.php');?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Manage Clubs</h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Show deletion success/error message -->
        <?php if($_SESSION['delmsg']!=""){ ?>
            <div class="errorWrap">
                <strong>Success :</strong> 
                <?php echo htmlentities($_SESSION['delmsg']); ?>
                <?php echo htmlentities($_SESSION['delmsg']=""); ?>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Club Details
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Club Name</th>
                                    <th>Description</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Creation Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
// Fetch all clubs
$sql = "SELECT 
            id,
            clubName,
            clubDescription,
            clubEmail,
            clubContact,
            clubAddress,
            creationDate,
            IsActive
        FROM tblclub
        ORDER BY id DESC";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;

if($query->rowCount() > 0)
{
    foreach($results as $row)
    { 
?>
<tr>
    <td><?php echo htmlentities($cnt); ?></td>
    <td><?php echo htmlentities($row->clubName); ?></td>
    <td><?php echo htmlentities($row->clubDescription); ?></td>
    <td><?php echo htmlentities($row->clubEmail); ?></td>
    <td><?php echo htmlentities($row->clubContact); ?></td>
    <td><?php echo htmlentities($row->clubAddress); ?></td>
    <td><?php echo htmlentities($row->creationDate); ?></td>
    <td>
        <?php 
            // If IsActive=1 => "Active", else "Inactive"
            echo ($row->IsActive==1) ? "Active" : "Inactive"; 
        ?>
    </td>
    <td>
        <!-- Edit button -->
        <a href="edit-club.php?clubid=<?php echo htmlentities($row->id); ?>">
            <button type="button" class="btn btn-info btn-circle">
                <i class="fa fa-edit"></i>
            </button>
        </a>
        <!-- Delete button -->
        <a href="manage-clubs.php?del=<?php echo htmlentities($row->id); ?>" 
           onclick="return confirm('Are you sure you want to delete?');">
            <button type="button" class="btn btn-danger btn-circle" style="margin-left:4px;">
                <i class="fa fa-times"></i>
            </button>
        </a>
    </td>
</tr>
<?php 
        $cnt++;
    }
} 
else 
{
    echo "<tr><td colspan='9'>No clubs found.</td></tr>";
} 
?>
                            </tbody>
                        </table>
                    </div><!-- /.col-lg-12 -->
                </div><!-- /.row (nested) -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
<!-- DataTables JavaScript -->
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
