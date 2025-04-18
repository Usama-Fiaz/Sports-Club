<?php
session_start();
error_reporting(E_ALL);

// 1) Ensure user is logged in
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: signin.php");
    exit;
}

include('includes/config.php'); 

// 2) Retrieve the order ID from URL
if (!isset($_GET['orderid']) || empty($_GET['orderid'])) {
    echo "No order specified.";
    exit;
}
$orderId = intval($_GET['orderid']);
$userId = $_SESSION['usrid'];

try {
    // 3) Fetch the order (must belong to this user)
    $orderSql = "SELECT id, orderNumber, orderDate, totalAmount, txntype, txnnumber, orderStatus
                 FROM orders
                 WHERE id = :orderId AND userId = :userId
                 LIMIT 1";
    $orderStmt = $dbh->prepare($orderSql);
    $orderStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $orderStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $orderStmt->execute();

    if ($orderStmt->rowCount() === 0) {
        echo "Order not found or you do not have permission to track this order.";
        exit;
    }
    $order = $orderStmt->fetch(PDO::FETCH_OBJ);

    // 4) Fetch the tracking history from order_track_history (example schema)
    // Suppose order_track_history has columns: id, orderNumber (int), status, remark, actionBy, postingDate
    // and references the parent's numeric ID in order_track_history.orderNumber
    $historySql = "SELECT
                     id,
                     orderId,
                     orderNumber,
                     status,
                     remark,
                     actionBy,
                     postingDate,
                     canceledBy
                   FROM order_track_history
                   WHERE orderId = :oid
                   ORDER BY postingDate ASC";
    $historyStmt = $dbh->prepare($historySql);
    $historyStmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
    $historyStmt->execute();
    $orderHistory = $historyStmt->fetchAll(PDO::FETCH_OBJ);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Order - <?php echo htmlentities($order->orderNumber); ?></title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/nivo-slider.css">
    <link rel="stylesheet" href="css/animate-text.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="css/color/skin-default.css" rel="stylesheet">

    <style>
        .track-order-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .order-info {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcumb Area -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Track Order</li>
            </ol>
        </div>
    </div>

    <div class="container track-order-container">
        <h2>Track Order</h2>

        <!-- Order Info -->
        <div class="order-info well">
            <p><strong>Order Number:</strong> <?php echo htmlentities($order->orderNumber); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlentities($order->orderDate); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlentities($order->totalAmount); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlentities($order->txntype); ?></p>
            <?php if (!empty($order->txnnumber)) { ?>
                <p><strong>Transaction Number:</strong> <?php echo htmlentities($order->txnnumber); ?></p>
            <?php } ?>
            <p><strong>Current Status:</strong> <?php echo htmlentities($order->orderStatus); ?></p>
        </div>

        <!-- Tracking History -->
        <h3>Order Tracking History</h3>
        <?php if ($historySql && count($historySql) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date / Time</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Action By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historySql as $track): ?>
                    <tr>
                        <td><?php echo htmlentities($track->postingDate); ?></td>
                        <td><?php echo htmlentities($track->status); ?></td>
                        <td><?php echo nl2br(htmlentities($track->remark)); ?></td>
                        <td><?php echo htmlentities($track->actionBy); ?></td>
                     
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tracking updates found for this order.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- JS Files -->
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.meanmenu.js"></script>
    <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
    <script src="js/nivo-slider/nivo-active.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
