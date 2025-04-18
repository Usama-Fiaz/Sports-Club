<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check admin session
if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else
{
    // Code for training booking deletion
    if(isset($_GET['bkdel']))
    {
        $bid = $_GET['bkdel'];
        $sql = "DELETE FROM tbltrainingbookings WHERE id = :bid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg'] = "Training booking deleted";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsSync | Manage Training Bookings</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
                <h1 class="page-header"> Manage Training Bookings</h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Show deletion message if any -->
        <?php if($_SESSION['delmsg']!=""){ ?>
            <div class="errorWrap">
                <strong>Success :</strong> 
                <?php echo htmlentities($_SESSION['delmsg']); ?>
                <?php echo htmlentities($_SESSION['delmsg']=""); ?>
            </div>
        <?php } ?>

        <div class="row" style="margin-top:1%">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       Manage Training Bookings
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table width="100%" 
                                       class="table table-striped table-bordered table-hover" 
                                       id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Booking Id</th>
                                            <th>Training Name</th>
                                            <th>User FullName</th>
                                            <th>Number of Members</th>
                                            <th>Status</th>
                                            <th>Booking Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
// Retrieve all training bookings
// Join with tbltraining for trainingName, and tblusers for FullName
// NOTE: If tblusers PK is 'Userid', then we match 'tu.Userid' with 'tb.UserId'
$sql = "SELECT 
            tb.id AS bkid,
            tb.BookingId,
            tt.trainingName,
            tu.FullName,
            tb.NumberOfMembers,
            tb.BookingStatus,
            tb.BookingDate
        FROM tbltrainingbookings tb
        LEFT JOIN tbltraining tt ON tt.id = tb.TrainingId
        LEFT JOIN tblusers tu ON tu.Userid = tb.UserId
        ORDER BY tb.id DESC";
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
                                            <td><?php echo htmlentities($row->BookingId); ?></td>
                                            <td><?php echo htmlentities($row->trainingName); ?></td>
                                            <td><?php echo htmlentities($row->FullName); ?></td>
                                            <td><?php echo htmlentities($row->NumberOfMembers); ?></td>
                                            <td>
<?php 
    $status = $row->BookingStatus;
    if($status == ""){
        echo htmlentities("Not Confirmed yet");    
    } else {
        echo htmlentities($status);        
    }
?>
                                            </td>
                                            <td><?php echo htmlentities($row->BookingDate); ?></td>
                                            <td>
                                                <!-- View booking details -->
                                                <a href="trainingbookingdetails.php?bkid=<?php echo htmlentities($row->bkid); ?>">
                                                    <i class="fa fa-file-text"></i>
                                                </a>
                                                &nbsp;
                                                <!-- Delete booking -->
                                                <a href="manage-training.php?bkdel=<?php echo htmlentities($row->bkid); ?>" 
                                                   onclick="return confirm('Are you sure you want to delete this booking?');">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
<?php
        $cnt++;
    }
}
else
{
    echo "<tr><td colspan='8' style='text-align:center; color:red;'>No bookings found.</td></tr>";
}
?>
                                    </tbody>
                                </table>
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
