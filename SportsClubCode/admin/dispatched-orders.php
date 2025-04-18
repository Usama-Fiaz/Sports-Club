<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession'])==0) {
    header('location:logout.php');
} else { 
    $sql = "SELECT 
              orders.id,
              orders.orderNumber,
              tblusers.FullName,
              orders.totalAmount,
              orders.orderDate,
              orders.orderStatus 
            FROM orders
            JOIN tblusers ON tblusers.Userid = orders.userId
            WHERE orders.orderStatus = 'Dispatched'";

    $query = $dbh->prepare($sql);
    $query->execute();
    $orders = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Dispatched Orders</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <?php include_once('includes/header.php');?>
        <?php include_once('includes/leftbar.php');?>
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
            <h1 class="page-header">Dispatched Orders</h1>
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-truck"></i> Dispatched Order Details</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table 
                          class="table table-striped table-bordered table-hover" 
                          id="dataTables-example"
                        >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order No.</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $cnt=1;
                                if($query->rowCount() > 0) {
                                    foreach($orders as $row) { ?>
                                        <tr>
                                            <td><?php echo $cnt++;?></td>
                                            <td><?php echo htmlentities($row->orderNumber);?></td>
                                            <td><?php echo htmlentities($row->FullName);?></td>
                                            <td><?php echo htmlentities($row->totalAmount);?></td>
                                            <td><?php echo htmlentities($row->orderDate);?></td>
                                            <td style="color:blue;"><?php echo htmlentities($row->orderStatus);?></td>
                                            <td>
                                                <a href="order-details.php?orderid=<?php echo $row->id;?>">
                                                    <i class="fa fa-file-text fa-2x" title="View Details"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else {
                                    echo "<tr><td colspan='7'>No Dispatched Orders Found</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->
                </div><!-- panel-body -->
            </div><!-- panel-default -->
        </div><!-- container-fluid -->
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
<script>
$(document).ready(function() {
    $('#dataTables-example').DataTable({ responsive: true });
});
</script>
</body>
</html>
<?php } ?>
