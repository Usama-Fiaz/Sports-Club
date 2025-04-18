<?php
session_start();
error_reporting(E_ALL);
include('includes/config.php'); // $dbh is your PDO connection

// 1) Ensure user is logged in
if (!isset($_SESSION['usrid']) || empty($_SESSION['usrid'])) {
    header("Location: signin.php");
    exit;
}

// 2) Retrieve order ID from URL
if (!isset($_GET['orderid']) || empty($_GET['orderid'])) {
    echo "No order specified.";
    exit;
}
$orderId = intval($_GET['orderid']);
$userId  = $_SESSION['usrid'];

try {
    // 3) Verify the order belongs to the user and is "Pending"
    //    We also fetch orderNumber so we can log it in order_tracking_history
    $sql = "SELECT id, orderNumber, orderStatus
            FROM orders
            WHERE id = :orderId
              AND userId = :userId
            LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->bindParam(':userId',  $userId,  PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        echo "Order not found or you do not have permission to cancel this order.";
        exit;
    }
    
    $order = $stmt->fetch(PDO::FETCH_OBJ);
    if ($order->orderStatus !== "Pending") {
        echo "Order cannot be cancelled as it is already processed or cancelled.";
        exit;
    }
    
    // 4) Begin transaction
    $dbh->beginTransaction();
    
    // Update the order status to "Cancelled"
    $updateSql = "UPDATE orders
                  SET orderStatus = 'Cancelled'
                  WHERE id = :orderId
                    AND userId = :userId";
    $updateStmt = $dbh->prepare($updateSql);
    $updateStmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $updateStmt->bindParam(':userId',  $userId,  PDO::PARAM_INT);
    $updateStmt->execute();
    
    // 5) Insert a record into order_tracking_history for the cancellation
    //    Columns: id, orderId, orderNumber, status, remark, actionBy, postingDate, canceledBy
    //    We'll store order->id in "orderId" (numeric) and order->orderNumber in "orderNumber" (string).
    $historySql = "INSERT INTO order_track_history
        (orderId, orderNumber, status, remark, actionBy, canceledBy, postingDate)
        VALUES
        (:orderId, :orderNumber, 'Cancelled', 'Order cancelled by user.', :userId, :canceledBy, NOW())";
    
    $historyStmt = $dbh->prepare($historySql);
    // order->id is numeric primary key
    $historyStmt->bindValue(':orderId',     $order->id,         PDO::PARAM_INT);
    // order->orderNumber is the string code
    $historyStmt->bindValue(':orderNumber', $order->orderNumber, PDO::PARAM_STR);
    // The user who cancelled is the same as the one logged in
    $historyStmt->bindValue(':userId',      $userId,            PDO::PARAM_INT);
    $historyStmt->bindValue(':canceledBy',  $userId,            PDO::PARAM_INT);
    
    $historyStmt->execute();
    
    // 6) Commit transaction
    $dbh->commit();
    
    echo "<script>alert('Order cancelled successfully.');</script>";
    echo "<script>window.location.href='my-orders.php';</script>";
    exit;
    
} catch (Exception $e) {
    $dbh->rollBack();
    echo "<script>alert('Error cancelling order: " . addslashes($e->getMessage()) . "');</script>";
    exit;
}
?>
