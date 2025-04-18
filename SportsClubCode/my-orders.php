<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is logged in; if not, redirect to logout (or login) page
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: logout.php");
    exit;
}

include('includes/config.php'); // Must create a PDO connection in $dbh

// Fetch orders for the logged-in user
$userId = $_SESSION['usrid'];
try {
    // Removed 'transactionType' from SELECT to match your actual table
    $sql = "SELECT id, orderNumber, orderDate, totalAmount, orderStatus
            FROM orders
            WHERE userId = :uid
            ORDER BY id DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $orders = ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
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
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <!-- Inline CSS for table styling -->
    <style>
        .my-orders-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .no-orders {
            text-align: center;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Header (assumed to display welcome message using session variables) -->
    <div id="home" class="header-slider-area">
        <?php include_once('includes/header.php'); ?>
    </div>

    <!-- Breadcumb Area -->
    <div class="breadcumb-area bg-overlay"
    style="background: #333; 
            background-image: none;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">My Orders</li>
            </ol>
        </div>
    </div>
   
    <!-- Main Content: My Orders -->
    <div class="container my-orders-container">
        <h1 class="section-title">My Orders</h1>
        <?php if (count($orders) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Order Date</th>
                            <!-- Removed Transaction Type column -->
                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): 
                            // If orderStatus is empty, display "Not Processed Yet"
                            $status = (!empty($order->orderStatus)) ? $order->orderStatus : "Not Processed Yet";
                        ?>
                        <tr>
                            <td><?php echo htmlentities($order->orderNumber); ?></td>
                            <td><?php echo htmlentities($order->orderDate); ?></td>
                            <!-- Removed Transaction Type -->
                            <td>$<?php echo htmlentities($order->totalAmount); ?></td>
                            <td><?php echo htmlentities($status); ?></td>
                            <td>
                                <a href="order-details.php?orderid=<?php echo $order->id; ?>" class="btn btn-primary">Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-orders">
                <h4>You have no orders yet.</h4>
                <a href="index.php" class="btn btn-success">Continue Shopping</a>
            </div>
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
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.mb.YTPlayer.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.nav.js"></script>
    <script src="js/animate-text.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
