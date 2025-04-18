<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | New Training Bookings</title>
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
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Manage New Training Bookings</h1>
            </div>
        </div>
        <!-- /.row -->
        <div class="row" style="margin-top:1%">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       All New Training Bookings (Status = NULL)
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table width="100%" class="table table-striped table-bordered table-hover" 
                                       id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Booking Id</th>
                                            <th>Training Name</th>
                                            <th>Full Name</th>
                                            <th>Number of Members</th>
                                            <th>Booking Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
// Show all new bookings where BookingStatus is NULL
$sql = "SELECT 
            tb.id AS bid,
            tb.BookingId,
            tt.trainingName,
            tu.FullName,
            tb.NumberOfMembers,
            tb.BookingStatus,
            tb.BookingDate
        FROM tbltrainingbookings tb
        LEFT JOIN tblusers tu ON tu.Userid = tb.UserId
        LEFT JOIN tbltraining tt ON tt.id = tb.TrainingId
        WHERE tb.BookingStatus IS NULL
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
                                            <td><?php echo htmlentities($row->BookingDate); ?></td>
                                            <td>
<?php 
    $status = $row->BookingStatus;
    echo ($status == "") ? "Not Confirmed yet" : htmlentities($status);
?>
                                            </td>
                                            <td>
                                                <a href="trainingbookingdetails.php?bkid=<?php echo $row->bid; ?>">
                                                    <i class="fa fa-file-text"></i>
                                                </a>
                                            </td>
                                        </tr>
<?php 
        $cnt++;
    }
}
else
{
    echo "<tr><td colspan='8' style='text-align:center; color:red;'>No Booking Yet</td></tr>";
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
