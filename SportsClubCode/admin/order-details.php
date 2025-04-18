<?php
session_start();
error_reporting(0);
include('includes/config.php'); // Must have a PDO $dbh connection

// 1) Check if admin is logged in
if(strlen($_SESSION["adminsession"]) == 0) {
    header('location:logout.php');
    exit;
}

// 2) Check for 'orderid' in GET
if (!isset($_GET['orderid']) || empty($_GET['orderid'])) {
    echo "No order specified.";
    exit;
}
$orderId = intval($_GET['orderid']);

try {
    // 3) Fetch main order info, user info, and address info
    $orderSql = "
        SELECT
            o.id             AS orderId,
            o.userId,
            o.addressId,
            o.totalAmount,
            o.txntype,
            o.txnnumber,
            o.orderNumber,
            o.orderDate,
            o.orderStatus,

            -- user columns from tblusers
            u.Userid         AS userTableId,
            u.FullName       AS userName,     /* or u.UserName if that's your column */
            u.Emailid        AS userEmail,
            u.PhoneNumber    AS userPhone,

            -- address columns from addresses table
            a.billingAddress,
            a.billingCity,
            a.billingState,
            a.billingCountry,
            a.billingPincode,
            a.shippingAddress,
            a.shippingCity,
            a.shippingState,
            a.shippingCountry,
            a.shippingPincode

        FROM orders o
        LEFT JOIN tblusers    u ON o.userId    = u.Userid
        LEFT JOIN addresses   a ON o.addressId = a.id
        WHERE o.id = :oid
        LIMIT 1
    ";
    $orderStmt = $dbh->prepare($orderSql);
    $orderStmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
    $orderStmt->execute();

    if ($orderStmt->rowCount() === 0) {
        echo "Order not found.";
        exit;
    }
    $order = $orderStmt->fetch(PDO::FETCH_OBJ);

    // We'll need orderNumber for items + track history
    $orderNumberStr = $order->orderNumber;

    // 4) Fetch items from order_details + products
    $itemsSql = "
        SELECT
            od.id              AS detailId,
            od.orderNumber,
            od.productId,
            od.quantity,
            od.orderDate       AS itemOrderDate,
            od.orderStatus     AS itemOrderStatus,

            p.productName,
            p.productImage1,
            p.productPrice,
            p.shippingCharge
        FROM order_details od
        LEFT JOIN products p ON od.productId = p.id
        WHERE od.orderNumber = :ordNum
    ";
    $itemsStmt = $dbh->prepare($itemsSql);
    $itemsStmt->bindParam(':ordNum', $orderNumberStr, PDO::PARAM_STR);
    $itemsStmt->execute();
    $orderItems = $itemsStmt->fetchAll(PDO::FETCH_OBJ);

    // 5) Fetch order history
    $historySql = "
        SELECT
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
        ORDER BY postingDate ASC
    ";
    $historyStmt = $dbh->prepare($historySql);
    $historyStmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
    $historyStmt->execute();
    $orderHistory = $historyStmt->fetchAll(PDO::FETCH_OBJ);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// 6) Handle the “Take Action” form
if (isset($_POST['updateOrder'])) {
    $newStatus = $_POST['newStatus'] ?? '';
    $remark    = trim($_POST['remark'] ?? '');
    $actionBy  = $_SESSION['adminsession']; // ID/username of the admin

    try {
        $dbh->beginTransaction();

        // Update main order status
        $updSql = "
            UPDATE orders
            SET orderStatus = :newStatus
            WHERE id = :oid
            LIMIT 1
        ";
        $updStmt = $dbh->prepare($updSql);
        $updStmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $updStmt->bindParam(':oid',       $orderId,   PDO::PARAM_INT);
        $updStmt->execute();

        // Insert into order_track_history
        $histSql = "
            INSERT INTO order_track_history
            (orderId, orderNumber, status, remark, actionBy, postingDate)
            VALUES
            (:oid, :ordNum, :status, :remark, :actionBy, NOW())
        ";
        $histStmt = $dbh->prepare($histSql);
        $histStmt->bindParam(':oid',      $orderId,        PDO::PARAM_INT);
        $histStmt->bindParam(':ordNum',   $orderNumberStr, PDO::PARAM_STR);
        $histStmt->bindParam(':status',   $newStatus,      PDO::PARAM_STR);
        $histStmt->bindParam(':remark',   $remark,         PDO::PARAM_STR);
        $histStmt->bindParam(':actionBy', $actionBy,       PDO::PARAM_STR);
        $histStmt->execute();

        $dbh->commit();

        echo "<script>alert('Order updated successfully!');</script>";
        // Refresh the same page
        echo "<script>window.location.href='order-details.php?orderid={$orderId}';</script>";
        exit;

    } catch (Exception $ex) {
        $dbh->rollBack();
        echo "<script>alert('Error updating order: " . addslashes($ex->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsSync | Order #<?php echo htmlentities($order->orderNumber); ?> Details</title>

    <!-- SB Admin 2 & vendor CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
    .well {
        background: #f8f8f8; 
        padding: 15px; 
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .page-header {
        margin-top: 20px;
    }
    </style>
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>
    <!-- /Navigation -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order #<?php echo htmlentities($order->orderNumber); ?> Details</h1>
            </div>
        </div>

        <!-- Order & Customer Info Panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Order & Customer Information
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- ORDER INFO -->
                    <div class="col-md-6">
                        <div class="well">
                            <h4>Order Info</h4>
                            <p><strong>Order No:</strong> <?php echo htmlentities($order->orderNumber); ?></p>
                            <p><strong>Order Date:</strong> <?php echo htmlentities($order->orderDate); ?></p>
                            <p><strong>Order Status:</strong> <?php echo htmlentities($order->orderStatus); ?></p>
                            <p><strong>Payment Method:</strong> <?php echo htmlentities($order->txntype); ?></p>
                            <?php if (!empty($order->txnnumber)): ?>
                                <p><strong>Txn Number:</strong> <?php echo htmlentities($order->txnnumber); ?></p>
                            <?php endif; ?>
                            <p><strong>Total Amount:</strong> $<?php echo htmlentities($order->totalAmount); ?></p>

                            <!-- Action + Print -->
                            <button class="btn btn-info" data-toggle="modal" data-target="#takeActionModal">
                                Take Action
                            </button>
                            <button class="btn btn-primary" onclick="window.print();">
                                Print
                            </button>
                        </div>
                    </div>

                    <!-- CUSTOMER/USER INFO -->
                    <div class="col-md-6">
                        <div class="well">
                            <h4>Customer/User Details</h4>
                            <p><strong>Name:</strong> <?php echo htmlentities($order->userName); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlentities($order->userEmail); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlentities($order->userPhone); ?></p>
                        </div>
                    </div>
                </div>

                <!-- ADDRESS INFO -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="well">
                            <h4>Billing Address</h4>
                            <p><?php echo nl2br(htmlentities($order->billingAddress)); ?></p>
                            <p><?php echo htmlentities($order->billingCity); ?>, <?php echo htmlentities($order->billingState); ?></p>
                            <p><?php echo htmlentities($order->billingCountry); ?> - <?php echo htmlentities($order->billingPincode); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="well">
                            <h4>Shipping Address</h4>
                            <p><?php echo nl2br(htmlentities($order->shippingAddress)); ?></p>
                            <p><?php echo htmlentities($order->shippingCity); ?>, <?php echo htmlentities($order->shippingState); ?></p>
                            <p><?php echo htmlentities($order->shippingCountry); ?> - <?php echo htmlentities($order->shippingPincode); ?></p>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS / ITEMS -->
                <div class="well">
                    <h4>Products in this Order</h4>
                    <?php if ($orderItems && count($orderItems) > 0): ?>
                        <?php
                        $grandTotal = 0;
                        ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Shipping</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $cnt = 1; foreach ($orderItems as $item):
                                    $pName   = $item->productName     ?? "N/A";
                                    $pImage  = $item->productImage1   ?? "";
                                    $pPrice  = $item->productPrice    ?? 0;
                                    $pShip   = $item->shippingCharge  ?? 0;
                                    $qty     = $item->quantity        ?? 1;
                                    $lineTotal = ($pPrice * $qty) + $pShip;
                                    $grandTotal += $lineTotal;
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td>
                                        <?php if (!empty($pImage)): ?>
                                            <img src="admin/productimages/<?php echo htmlentities($pImage); ?>"
                                                 alt="Product"
                                                 style="width:60px; height:auto;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlentities($pName); ?></td>
                                    <td>$<?php echo number_format($pPrice, 2); ?></td>
                                    <td>$<?php echo number_format($pShip, 2); ?></td>
                                    <td><?php echo htmlentities($qty); ?></td>
                                    <td>$<?php echo number_format($lineTotal, 2); ?></td>
                                </tr>
                                <?php $cnt++; endforeach; ?>
                                <tr>
                                    <td colspan="6" align="right"><strong>Grand Total:</strong></td>
                                    <td><strong>$<?php echo number_format($grandTotal, 2); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No items found for this order.</p>
                    <?php endif; ?>
                </div>

                <!-- ORDER HISTORY -->
                <div class="well">
                    <h4>Order History</h4>
                    <?php if ($orderHistory && count($orderHistory) > 0): ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action By</th>
                                    <th>Order Number</th>
                                    <th>canceledBy</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderHistory as $hist): ?>
                                <tr>
                                    <td><?php echo htmlentities($hist->postingDate); ?></td>
                                    <td><?php echo htmlentities($hist->status); ?></td>
                                    <td><?php echo nl2br(htmlentities($hist->remark)); ?></td>
                                    <td><?php echo htmlentities($hist->actionBy); ?></td>
                                    <td><?php echo htmlentities($hist->orderNumber); ?></td>
                                    <td><?php echo htmlentities($hist->canceledBy); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No history found for this order.</p>
                    <?php endif; ?>
                </div>
            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- col-lg-12 -->
</div><!-- row -->
</div><!-- page-wrapper -->
</div><!-- wrapper -->

<!-- Take Action Modal -->
<div class="modal fade" id="takeActionModal" tabindex="-1" role="dialog" aria-labelledby="takeActionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="takeActionLabel">Take Action on Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Hidden field for order ID (optional if we rely on $orderId server-side) -->
          <input type="hidden" name="orderId" value="<?php echo $order->orderId; ?>">

          <div class="form-group">
              <label for="newStatus">New Status</label>
              <select name="newStatus" id="newStatus" class="form-control" required>
                  <option value="">--Select Status--</option>
                  <option value="Packed">Packed</option>
                  <option value="Dispatched">Dispatched</option>
                  <option value="In Transit">In Transit</option>
                  <option value="Delivered">Delivered</option>
              </select>
          </div>
          <div class="form-group">
              <label for="remark">Remark</label>
              <textarea name="remark" id="remark" class="form-control" rows="3" placeholder="Enter any remark"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="updateOrder" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
