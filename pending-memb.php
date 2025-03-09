<?php
// Fetch pending requests
$memreqsql = "SELECT DISTINCT m.*, u.username
FROM membership_requests m
INNER JOIN users_login u ON m.req_userid = u.id
WHERE m.status = 'pending'";

$result_membreq = mysqli_query($conn, $memreqsql);

if(mysqli_num_rows($result_membreq) > 0) {
    while ($row = mysqli_fetch_assoc($result_membreq)) {
        $req_type = $row['request_type'];
        $req_membType = $row['requested_membership_type'];
        $req_userid = $row['req_userid'];
        $username = $row['username'];
        $req_id=$row['_id'];
        ?>

        <form action="" method="post">
            <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Request type(membership):</strong> <?= htmlspecialchars($req_type); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>

            <?php if ($req_type == 'upgrade'): ?>
                <button class="approve-button" name="approve_upgrade" type="submit">Approve Upgrade</button>
                <button class="reject-button" name="reject_upgrade" type="submit">Reject Upgrade</button>
            <?php elseif ($req_type == 'renewal'): ?>
                <button class="approve-button" name="approve_renewal" type="submit">Approve Renewal</button>
                <button class="reject-button" name="reject_renewal" type="submit">Reject Renewal</button>
            <?php endif; ?>

            <input type="hidden" name="req_userid" value="<?= htmlspecialchars($req_userid); ?>">
            <input type="hidden" name="membreq_type" value="<?= htmlspecialchars($req_type); ?>">
            <input type="hidden" name="memb_type" value="<?= htmlspecialchars($req_membType); ?>">
            <input type="hidden" name="req_id" value="<?= htmlspecialchars($req_id); ?>">
      
        </form>

        <?php
    }
}

// Handle Renewal Approval
if (isset($_POST['approve_renewal'])) {
    $userid = $_POST['req_userid'];
    $reqid=$_POST['req_id'];
    $updateMembInfo = "UPDATE users_info SET membership_expiry_date = DATE_ADD(NOW(), INTERVAL 1 YEAR) WHERE id = '$userid'";
    mysqli_query($conn, $updateMembInfo);

    $updateMembreq = "UPDATE membership_requests SET status='complete' WHERE _id='$reqid' AND request_type='renewal'";
    mysqli_query($conn, $updateMembreq);

    $sql="SELECT pay_id FROM memb_payment WHERE user_id='$userid' AND status='pending'";
    $result=mysqli_query($conn,$sql);
    $payid= mysqli_fetch_assoc($result);
    $payid=$payid['pay_id'];

    $paySql="UPDATE memb_payment
                SET status = 'complete'
                WHERE user_id=$userid AND pay_id='$payid'";
                mysqli_query($conn,$paySql);

    $message = "Your membership renewal request has been approved. Your new expiry date is updated.";
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','membership')";
    mysqli_query($conn, $insertNotification);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Upgrade Approval
if (isset($_POST['approve_upgrade'])) {
    $userid = $_POST['req_userid'];
    $newType = $_POST['memb_type'];
    $reqid=$_POST['req_id'];

    $updateMembInfo = "UPDATE users_info SET membership='$newType' WHERE id='$userid'";
    mysqli_query($conn, $updateMembInfo);

    $updateMembreq = "UPDATE membership_requests SET status='complete' WHERE _id='$reqid' AND request_type='upgrade'";
    mysqli_query($conn, $updateMembreq);

    $message = "Your membership upgrade request has been approved. You are now a $newType member.";
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','membership')";
    mysqli_query($conn, $insertNotification);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Upgrade Rejection
if (isset($_POST['reject_upgrade'])) {
    $userid = $_POST['req_userid'];
    $reqid=$_POST['req_id'];
    $updateRequest = "UPDATE membership_requests SET status='rejected' WHERE _id='$reqid' AND request_type='upgrade'";

    $message = "Your membership upgrade request has been denied.";
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','membership')";
    mysqli_query($conn, $insertNotification);
   
    mysqli_query($conn, $updateRequest);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Renewal Rejection
if (isset($_POST['reject_renewal'])) {
    $userid = $_POST['req_userid'];
    $reqid=$_POST['req_id'];
    $updateRequest = "UPDATE membership_requests SET status='rejected' WHERE _id='$reqid' AND request_type='renewal'";
    mysqli_query($conn, $updateRequest);

    $message = "Your membership renewal request has been denied."; 
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','membership')";
    mysqli_query($conn, $insertNotification);
   
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>