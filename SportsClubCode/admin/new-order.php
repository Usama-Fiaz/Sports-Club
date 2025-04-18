<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if admin is logged in
if(strlen($_SESSION['adminsession'])==0) {   
    header('location:logout.php');
    exit;
} else { 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Manage New Orders</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
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
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>
    <!-- /Navigation -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Manage New Orders</h1>
            </div>
        </div>

        <!-- /.row -->
        <div class="row" style="margin-top:1%">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        New Orders (Not Yet Confirmed)
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Ensure you use the alias "orderid" consistently -->
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Number</th>
                                            <th>Full Name</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    // Fetch orders with a "Pending" status (new orders)
                                    $sql = "SELECT 
                                                orders.id AS orderid,
                                                orders.OrderNumber,
                                                orders.OrderDate,
                                                orders.OrderStatus,
                                                tblusers.FullName
                                            FROM orders
                                            LEFT JOIN tblusers ON tblusers.Userid = orders.UserId
                                            WHERE orders.OrderStatus = 'Pending'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;

                                    if($query->rowCount() > 0) {
                                        foreach($results as $row) { ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($row->OrderNumber); ?></td>
                                                <td><?php echo htmlentities($row->FullName); ?></td>
                                                <td><?php echo htmlentities($row->OrderDate); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $row->OrderStatus;
                                                    if($status=="") {
                                                        echo htmlentities("Not Confirmed yet");
                                                    } else {
                                                        echo htmlentities($status);
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <!-- Use orderid from SQL alias; order-details.php should use $_GET['orderid'] -->
                                                    <a href="order-details.php?orderid=<?php echo htmlentities($row->orderid); ?>">
                                                        <i class="fa fa-file-text"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No new orders found.</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div><!-- /.col-lg-12 -->
                        </div><!-- /.row (nested) -->
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->
            </div><!-- /.col-lg-12 -->
        </div><!-- /.row -->
    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

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
