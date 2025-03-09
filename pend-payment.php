<?php
$paymentsql = "SELECT p.*, u.username
           FROM pending_payment p
           INNER JOIN users_login u ON p.req_userid = u.id
           WHERE p.status = 'pending'";

$presult = mysqli_query($conn, $paymentsql);

if(mysqli_num_rows($presult) > 0) {
    while ($row = mysqli_fetch_assoc($presult)) {
        $userid = $row['req_userid'];
        $username = $row['username'];
        $amount_paid = $row['amount_paid'];
        $request_id = $row['id'];
        $reqDate= $row['requested_date'];
        ?>
        <form action="" method="post">
        <p><strong>Requested User:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?= number_format($amount_paid, 2); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
            
            <button class="approve-button" name="approve_payment" type="submit">Approve</button>
            <button class="reject-button" name="reject_payment" type="submit">Reject</button> 
            
            <input type="hidden" name="payment_id" value="<?= htmlspecialchars($request_id); ?>">
            <input type="hidden" name="userid" value="<?= htmlspecialchars($userid); ?>">
            <input type="hidden" name="amount_paid" value="<?= htmlspecialchars($amount_paid); ?>">
        </form>
        <?php
    }
}

// Handle Approve Request
if (isset($_POST['approve_payment'])) {
    $payment_id = $_POST['payment_id'];
    $userid = $_POST['userid'];
    $amount_paid = $_POST['amount_paid'];


    // Approve the payment
    $updatePendPayment = "UPDATE pending_payment SET status = 'Approved', approved_date = NOW() WHERE id = '$payment_id'";
    mysqli_query($conn, $updatePendPayment);

    // Update users_info: Reset due & extend due date by 1 month from requested date
    $updatePayment = "UPDATE users_info 
                       SET payment_due = 0, 
                       status='paid',
                           payment_due_date = DATE_ADD('$reqDate', INTERVAL 1 MONTH) 
                       WHERE id = '$userid'";
    mysqli_query($conn, $updatePayment);

    $paySql="UPDATE system_payment 
                SET status = 'complete'
                WHERE user_id=$userid ";
                mysqli_query($conn,$paySql);

    $message = "Payment approved! Next due date: " . date('Y-m-d', strtotime($requested_date . ' +1 month')); 
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','payment')";
    mysqli_query($conn, $insertNotification);

    echo "Payment approved. Due date updated.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Reject Request
if (isset($_POST['reject_payment'])) {
    $payment_id = $_POST['payment_id'];

    // Update status to 'Rejected'
    $rejectPaymentQuery = "UPDATE pending_payment SET status = 'Rejected' WHERE id= '$payment_id'";
    mysqli_query($conn, $rejectPaymentQuery);

    $_SESSION['payment_error'] = "Payment request was rejected.";
    
    $message = "Your request for payment was rejected."; 
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','payment')";
    mysqli_query($conn, $insertNotification);

    echo "The request was rejected.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?> 