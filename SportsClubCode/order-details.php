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
    $orderSql = "SELECT id, orderNumber, orderDate, totalAmount, txntype, txnnumber, orderStatus, addressId
                 FROM orders
                 WHERE id = :orderId AND userId = :userId
                 LIMIT 1";
    $orderStmt = $dbh->prepare($orderSql);
    $orderStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $orderStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $orderStmt->execute();

    if ($orderStmt->rowCount() === 0) {
        echo "Order not found or you do not have permission to view this order.";
        exit;
    }
    $order = $orderStmt->fetch(PDO::FETCH_OBJ);

    // 4) Fetch the billing/shipping addresses (via addressId)
    $addressSql = "SELECT * FROM addresses
                   WHERE id = :addressId AND userId = :userId
                   LIMIT 1";
    $addressStmt = $dbh->prepare($addressSql);
    $addressStmt->bindParam(':addressId', $order->addressId, PDO::PARAM_INT);
    $addressStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $addressStmt->execute();
    $address = ($addressStmt->rowCount() > 0) ? $addressStmt->fetch(PDO::FETCH_OBJ) : null;

    // 5) Fetch order items from order_details + products
    $itemsSql = "SELECT
    od.id AS detailId,
    od.orderNumber,
    od.productId,
    od.quantity,
    od.orderDate AS itemOrderDate,
    od.orderStatus AS itemOrderStatus,

    p.productName,
    p.productImage1,
    p.productPrice,
    p.shippingCharge

 FROM order_details od
 LEFT JOIN products p ON od.productId = p.id
 WHERE od.orderNumber = :ordNum";
$itemsStmt = $dbh->prepare($itemsSql);
$itemsStmt->bindParam(':ordNum', $order->orderNumber, PDO::PARAM_STR);
$itemsStmt->execute();
$orderItems = $itemsStmt->fetchAll(PDO::FETCH_OBJ);

    // 6) Calculate grand total (optional if total is in orders table)
    $grandTotal = 0;
    foreach ($orderItems as $item) {
        $lineTotal = $item->productPrice * $item->quantity;
        $grandTotal += $lineTotal;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - <?php echo htmlentities($order->orderNumber); ?></title>
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
        .order-details-container {
            margin-top: 80px;
            margin-bottom: 50px;
        }
        .order-summary, .address-info {
            margin-bottom: 30px;
        }
        .order-items-table img {
            max-width: 80px;
            height: auto;
        }
        .order-actions {
            text-align: right;
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
                <li class="active">Order Details</li>
            </ol>
        </div>
    </div>

    <div class="container order-details-container">
        <h2>Order Details</h2>

        <!-- Order Summary -->
        <div class="order-summary well">
            <p><strong>Order Number:</strong> <?php echo htmlentities($order->orderNumber); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlentities($order->orderDate); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlentities($order->totalAmount); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlentities($order->txntype); ?></p>
            <?php if (!empty($order->txnnumber)) { ?>
                <p><strong>Transaction Number:</strong> <?php echo htmlentities($order->txnnumber); ?></p>
            <?php } ?>
            <p><strong>Order Status:</strong> <?php echo htmlentities($order->orderStatus); ?></p>

            <!-- Right-aligned buttons -->
            <div class="order-actions">
                <a href="track-order.php?orderid=<?php echo $order->id; ?>" class="btn btn-info btn-sm">Track Order</a>
                <a href="#" class="btn btn-danger btn-sm"
                   onclick="openCancelPopup(<?php echo $order->id; ?>); return false;">
                   Cancel Order
                </a>
            </div>
        </div>

        <!-- Address Info -->
        <div class="address-info well">
            <?php if ($address): ?>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Billing Address</h4>
                        <p><?php echo nl2br(htmlentities($address->billingAddress)); ?></p>
                        <p><?php echo htmlentities($address->billingCity); ?>, <?php echo htmlentities($address->billingState); ?></p>
                        <p><?php echo htmlentities($address->billingCountry); ?> - <?php echo htmlentities($address->billingPincode); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Shipping Address</h4>
                        <p><?php echo nl2br(htmlentities($address->shippingAddress)); ?></p>
                        <p><?php echo htmlentities($address->shippingCity); ?>, <?php echo htmlentities($address->shippingState); ?></p>
                        <p><?php echo htmlentities($address->shippingCountry); ?> - <?php echo htmlentities($address->shippingPincode); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p>No address information available for this order.</p>
            <?php endif; ?>
        </div>

        <!-- Order Items -->
        <h3>Products in this Order</h3>
        <?php if ($orderItems && count($orderItems) > 0): ?>
            <table class="table table-bordered order-items-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Item Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $calculatedGrandTotal = 0;
                    foreach ($orderItems as $item):
                        $itemTotal = $item->productPrice * $item->quantity;
                        $calculatedGrandTotal += $itemTotal;
                    ?>
                    <tr>
                        <td>
                            <?php
                            $imgPath = !empty($item->productImage1)
                                ? "admin/productimages/" . htmlentities($item->productImage1)
                                : "images/placeholder.png";
                            ?>
                            <img src="<?php echo $imgPath; ?>" alt="<?php echo htmlentities($item->productName); ?>">
                        </td>
                        <td><?php echo htmlentities($item->productName); ?></td>
                        <td>$<?php echo htmlentities($item->productPrice); ?></td>
                        <td><?php echo htmlentities($item->quantity); ?></td>
                        <td>$<?php echo number_format($itemTotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" align="right"><strong>Grand Total:</strong></td>
                        <td><strong>$<?php echo number_format($calculatedGrandTotal, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items found for this order.</p>
        <?php endif; ?>
    </div>

    <!-- Cancel Order Popup -->
    <script>
    function openCancelPopup(orderId) {
        if (confirm("Are you sure you want to cancel this order?")) {
            // Redirect to cancel-order.php or handle logic
            window.location.href = "cancelorder.php?orderid=" + orderId;
        }
    }
    </script>

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
