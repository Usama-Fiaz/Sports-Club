<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['usrid'])==0){
    header('location:logout.php');
} else {

    // Cancel training booking
    if(isset($_POST['cancelTrainingBooking'])) {
        $uid = $_SESSION['usrid'];
        $bkngid = intval($_GET['bkid']);
        $cancelremark = $_POST['cancellationremark'];
        $status = "Cancelled";

        $sql = "UPDATE tbltrainingbookings 
                SET UserCancelRemark = :cancelremark, BookingStatus = :status 
                WHERE UserId = :uid AND id = :bkngid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
        $query->bindParam(':bkngid', $bkngid, PDO::PARAM_STR);
        $query->bindParam(':cancelremark', $cancelremark, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        echo "<script>alert('Training Booking Cancelled');</script>";
        echo "<script>window.location.href='my-bookings.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Training Booking Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/faicons.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
@media print {
    a[href]:after {
        content: "" !important;
    }
    .btn, .modal, .fa-print, .training-name + a, .breadcrumb, .header-slider-area, .breadcrumb-area, .navbar, .footer {
        display: none !important;
    }
}
</style>
</head>
<body>
<div class="wrapper single-blog">
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <div class="breadcumb-area bg-overlay" style="background:#333;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Training Booking Details</li>
            </ol>
        </div>
    </div>

    <div class="single-blog-area ptb100 fix">
        <div class="container">
            <div class="row">
                <?php include_once('includes/myaccountbar.php'); ?>
                <div class="col-md-8 col-sm-7">
                    <div class="single-blog-body">
<?php
$uid = $_SESSION['usrid'];
$bkngid = intval($_GET['bkid']);

$sql = "SELECT 
            tbltrainingbookings.BookingId,
            tbltrainingbookings.BookingDate,
            tbltrainingbookings.BookingStatus,
            tbltrainingbookings.UserRemark,
            tbltrainingbookings.NumberOfMembers,
            tbltrainingbookings.UserCancelRemark,
            tbltrainingbookings.AdminRemark,
            tbltrainingbookings.LastUpdationDate,
            tbltraining.TrainingName,
            tbltraining.TrainingLocation,
            tbltraining.TrainingStartDate,
            tbltraining.TrainingEndDate,
            tbltraining.id AS trainingid
        FROM tbltrainingbookings
        LEFT JOIN tbltraining ON tbltraining.id = tbltrainingbookings.TrainingId
        WHERE tbltrainingbookings.UserId = :uid AND tbltrainingbookings.id = :bkngid";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $uid, PDO::PARAM_STR);
$query->bindParam(':bkngid', $bkngid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        $trainingEndDate = $row->TrainingEndDate;
        $bstatus = $row->BookingStatus;
?>
<div class="Leave-your-thought mt50" id="printArea">
    <h3 class="aside-title uppercase">
        <?php echo htmlentities($row->trainingid); ?>
            <?php echo htmlentities($row->TrainingName); ?>
        </a> Booking Details
    </h3>
    <table class="table table-bordered">
        <tr>
            <th>Booking ID</th>
            <td><?php echo htmlentities($row->BookingId); ?></td>
            <th>Booking Date</th>
            <td><?php echo htmlentities($row->BookingDate); ?></td>
        </tr>
        <tr>
            <th>Number of Members</th>
            <td><?php echo htmlentities($row->NumberOfMembers); ?></td>
            <th>User Remark</th>
            <td><?php echo htmlentities($row->UserRemark); ?></td>
        </tr>
        <tr>
            <th>Training Name</th>
            <td colspan="3">
                <a href="training-details.php?trnid=<?php echo htmlentities($row->trainingid); ?>">
                    <?php echo htmlentities($row->TrainingName); ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>Training Date</th>
            <td><?php echo htmlentities($row->TrainingStartDate); ?> To <?php echo htmlentities($row->TrainingEndDate); ?></td>
            <th>Location</th>
            <td><?php echo htmlentities($row->TrainingLocation); ?></td>
        </tr>
        <tr>
            <th>Booking Status</th>
            <td colspan="3"><?php echo $bstatus ? htmlentities($bstatus) : "Not Confirmed Yet"; ?></td>
        </tr>
        <?php if ($row->AdminRemark) { ?>
        <tr>
            <th>Admin Remark</th>
            <td colspan="3"><?php echo htmlentities($row->AdminRemark); ?></td>
        </tr>
        <?php } ?>
        <?php if ($row->UserCancelRemark) { ?>
        <tr>
            <th>User Cancellation Remark</th>
            <td colspan="3"><?php echo htmlentities($row->UserCancelRemark); ?></td>
        </tr>
        <?php } ?>
        <?php if ($row->LastUpdationDate) { ?>
        <tr>
            <th>Last Update</th>
            <td colspan="3"><?php echo htmlentities($row->LastUpdationDate); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2" align="center">
                <button class="btn btn-info" data-toggle="modal" data-target="#cancelModal">Cancel Booking</button>
            </td>
            <td colspan="2" align="center">
                <i class="fa fa-print fa-2x" onclick="printSection()" style="cursor:pointer;"></i>
            </td>
        </tr>
    </table>
</div>
<?php } } else { echo "<p>No booking found</p>"; } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal fade" role="dialog" style="margin-top:10%">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h4 class="modal-title">Cancel Training Booking</h4></div>
          <div class="modal-body">
            <?php
            $currentDate = date('Y-m-d');
            if (($currentDate <= $trainingEndDate) && $bstatus == "") {
            ?>
            <form method="post">
                <textarea name="cancellationremark" placeholder="Reason for cancellation" class="form-control" required></textarea><br>
                <button type="submit" name="cancelTrainingBooking" class="btn btn-danger">Cancel Booking</button>
            </form>
            <?php } else { ?>
                <p>This booking cannot be cancelled at this time.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</div>

<!-- JS -->
<script>
function printSection() {
    var printContent = document.getElementById("printArea").innerHTML;
    var win = window.open("", "", "width=800,height=700");
    win.document.write('<html><head><title>Print Booking</title>');
    win.document.write('<link rel="stylesheet" href="css/bootstrap.min.css">');
    win.document.write('</head><body>');
    win.document.write(printContent);
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    win.print();
    win.close();
}
</script>
<script src="js/vendor/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
