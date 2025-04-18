<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if admin is logged in
if(strlen($_SESSION["adminsession"])==0) {
    header('location:logout.php');
} else {

    // Fetch In Transit Orders
    $sql = "SELECT 
                orders.id,
                orders.orderNumber,
                tblusers.FullName,
                orders.totalAmount,
                orders.orderDate,
                orders.orderStatus
            FROM orders
            JOIN tblusers ON tblusers.Userid = orders.userId
            WHERE orders.orderStatus = 'In Transit'";

    $query = $dbh->prepare($sql);
    $query->execute();
    $orders = $query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | In Transit Orders</title>

    <!-- SB Admin 2 / Bootstrap 3 CSS -->
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
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap{
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
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
        <?php include_once('includes/header.php');?>
        <?php include_once('includes/leftbar.php');?>
    </nav>
    <!-- /Navigation -->

    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">In Transit Orders</h1>
                </div>
            </div>
            <!-- /.row -->

            <!-- Panel for In Transit Orders -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-truck"></i> In Transit Order Details
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table 
                                  width="100%"
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
                                    $cnt = 1;
                                    if($query->rowCount() > 0) {
                                        foreach($orders as $row) { ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($row->orderNumber); ?></td>
                                                <td><?php echo htmlentities($row->FullName); ?></td>
                                                <td><?php echo htmlentities($row->totalAmount); ?></td>
                                                <td><?php echo htmlentities($row->orderDate); ?></td>
                                                <td style="color:blue;"><?php echo htmlentities($row->orderStatus); ?></td>
                                                <td>
                                                    <a href="order-details.php?orderid=<?php echo $row->id; ?>">
                                                        <i class="fa fa-file-text fa-2x" title="View Details"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php 
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No In Transit Orders Found</td></tr>";
                                    } ?>
                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                </div><!-- /.col-lg-12 -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>

<!-- DataTables -->
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
