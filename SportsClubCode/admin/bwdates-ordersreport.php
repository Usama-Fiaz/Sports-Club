<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else{ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SportsSync | Between Dates Order Report</title>
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
        <?php include_once('includes/header.php');?>
        <?php include_once('includes/leftbar.php');?>
    </nav>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Between Dates Order Report</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter Orders</div>
                    <div class="panel-body">
                        <form method="post">                                
                            <div class="row">
                                <div class="col-2">From Date</div>
                                <div class="col-4"><input type="date" name="fromdate" class="form-control" required></div>
                            </div>
                            <div class="row" style="margin-top:1%;">
                                <div class="col-2">To Date</div>
                                <div class="col-4"><input type="date" name="todate" class="form-control" required></div>
                            </div>
                            <div class="row" style="margin-top:1%;">
                                <div class="col-6" align="center">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($_POST['submit'])) { 
            $fdate=$_POST['fromdate'];
            $tdate=$_POST['todate'];
        ?>
        <div class="panel-body">
            <h4 align="center" style="color:blue">Orders Report From <?php echo $fdate;?> To <?php echo $tdate;?></h4>
            <hr />
            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Order By</th>
                        <th>Order Amount</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT orders.id, orders.orderNumber, orders.totalAmount, orders.orderStatus, orders.orderDate, tblusers.FullName FROM orders JOIN tblusers ON tblusers.Userid = orders.userId WHERE orderDate BETWEEN :fdate AND :tdate";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':fdate',$fdate,PDO::PARAM_STR);
                    $query->bindParam(':tdate',$tdate,PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if($query->rowCount() > 0) {
                        foreach($results as $row) { ?>
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($row->orderNumber);?></td>
                                <td><?php echo htmlentities($row->FullName);?></td>
                                <td><?php echo htmlentities($row->totalAmount);?></td>
                                <td><?php echo htmlentities($row->orderDate);?></td>
                                <td><?php echo htmlentities($row->orderStatus);?></td>
                                <td>
                                    <a href="order-details.php?orderid=<?php echo $row->id; ?>" target="_blank">
                                        <i class="fa fa-file fa-2x" title="View Order Details"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $cnt++; 
                        } 
                    } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
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