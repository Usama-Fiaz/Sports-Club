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
    // Code for remark (status) update
    if(isset($_POST['updatebooking']))
    {
        $bkngid = intval($_GET['bkid']);
        $adminremark = $_POST['adminremark'];
        $status = $_POST['status'];
        
        $sql = "UPDATE tbltrainingbookings 
                SET AdminRemark = :adminremark, BookingStatus = :status 
                WHERE id = :bkngid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bkngid', $bkngid, PDO::PARAM_STR);
        $query->bindParam(':adminremark', $adminremark, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        
        echo "<script>alert('Success: Booking details updated.');</script>";
        echo "<script>window.location.href='all-trainingbooking.php'</script>"; 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Training Booking Details</title>

    <!-- Bootstrap Core CSS -->
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
        <?php include_once('includes/header.php'); ?>
        <!-- Left Sidebar -->
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Training Booking Details</h1>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                <?php
                // Fetch booking details
                $bid = intval($_GET['bkid']);     
                $sql = "SELECT 
                            tbltrainingbookings.id AS tid,
                            tbltrainingbookings.BookingId,
                            tbltraining.TrainingName,
                            tbltraining.id AS trainingid,
                            tblusers.FullName,
                            tbltrainingbookings.NumberOfMembers,
                            tbltrainingbookings.BookingStatus,
                            tbltrainingbookings.BookingDate,
                            tblusers.Emailid,
                            tblusers.PhoneNumber,
                            tbltrainingbookings.UserRemark,
                            tbltrainingbookings.UserCancelRemark,
                            tbltrainingbookings.AdminRemark,
                            tbltrainingbookings.LastUpdationDate
                        FROM tbltrainingbookings
                        LEFT JOIN tblusers ON tblusers.Userid = tbltrainingbookings.UserId
                        LEFT JOIN tbltraining ON tbltraining.id = tbltrainingbookings.TrainingId
                        WHERE tbltrainingbookings.id = :bid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':bid', $bid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if($query->rowCount() > 0)
                {
                    foreach($results as $row)
                    { 
                ?>              
                    <div class="panel-heading">
                        #<?php echo htmlentities($row->BookingId); ?> Details
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <table width="100%" class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <th>Booking Id</th>
                                        <td><?php echo htmlentities($row->BookingId); ?></td>
                                        <th>Booking Date</th>
                                        <td><?php echo htmlentities($row->BookingDate); ?></td>
                                        <th>Training Name</th>
                                        <td>
                                            <a href="edit-training.php?sid=<?php echo htmlentities($row->trainingid); ?>" target="_blank">
                                                <?php echo htmlentities($row->TrainingName); ?>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Number of Members</th>
                                        <td><?php echo htmlentities($row->NumberOfMembers); ?></td>
                                        <th>Booking Status</th>
                                        <td colspan="3">
                                            <?php 
                                            $status = $row->BookingStatus;
                                            if($status == ""){
                                                echo htmlentities("Not Confirmed yet");    
                                            } else {
                                                echo htmlentities($status);        
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Full Name</th>
                                        <td><?php echo htmlentities($row->FullName); ?></td>
                                        <th>Email Id</th>
                                        <td><?php echo htmlentities($row->Emailid); ?></td>
                                        <th>Phone Number</th>
                                        <td><?php echo htmlentities($row->PhoneNumber); ?></td>
                                    </tr>

                                    <tr>
                                        <th>User Remark</th>
                                        <td colspan="5"><?php echo htmlentities($row->UserRemark); ?></td>
                                    </tr>

                                    <?php if($row->UserCancelRemark != ""){ ?>
                                    <tr>
                                        <th>User Cancellation Remark</th>
                                        <td colspan="5"><?php echo htmlentities($row->UserCancelRemark); ?></td>
                                    </tr>
                                    <?php } ?>

                                    <?php if($row->AdminRemark != ""){ ?>
                                    <tr>
                                        <th>Admin Remark</th>
                                        <td colspan="5"><?php echo htmlentities($row->AdminRemark); ?></td>
                                    </tr>
                                    <?php } ?>

                                    <?php if($row->LastUpdationDate != ""){ ?>
                                    <tr>
                                        <th>Last Updation Date</th>
                                        <td colspan="5"><?php echo htmlentities($row->LastUpdationDate); ?></td>
                                    </tr>
                                    <?php } ?>
                                </table>

                                <!-- Show Take Action if not confirmed/cancelled -->
                                <?php if($status == ""){ ?>
                                <div class="form-group" align="center">
                                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
                                        Take Action
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                <?php 
                    } // end foreach
                } // end if
                ?>

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

<!-- Take action modal -->
<div id="myModal" class="modal fade" role="dialog" style="margin-top:10%">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Admin take action</h4>
      </div>
      <div class="modal-body">
        <form name="adminremark" method="post">
          <p>
            <textarea placeholder="Admin remark" class="form-control" name="adminremark" required="true"></textarea>
          </p>
          <p>
            <select name="status" required="true" class="form-control">
              <option value="Confirmed">Confirmed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </p>
          <p>
            <button type="submit" class="btn btn-info btn-lg" name="updatebooking">Submit</button>
          </p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
