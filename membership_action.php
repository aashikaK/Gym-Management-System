<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];
$action = $_GET['action'] ?? '';

$user_id = null;
$sql = "SELECT id, membership, membership_expiry_date FROM users_info WHERE id = (SELECT id FROM users_login WHERE username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id, $membership, $membership_expiry_date);
$stmt->fetch();
$stmt->close();

// Check if renewal is allowed (e.g., within 30 days of expiry)
$current_date = new DateTime();
$expiry_date = new DateTime($membership_expiry_date);
$diff = $expiry_date->diff($current_date);
$allow_renewal = ($expiry_date <= $current_date) || ($diff->days <= 30);

if ($action === 'renew' && $allow_renewal) {
    // Insert renewal request into the membership_request table
    // $request_type = 'renew';
    // $status = 'Pending';  // Initial status as pending
    // $requested_date = $current_date->format('Y-m-d');
    
    // // Insert renewal request into the membership_request table
    // $request_sql = "INSERT INTO membership_requests (req_userid, request_type, requested_membership_type, requested_date, status) 
    //                 VALUES (?, ?, ?, ?, ?)";
    // $stmt = $conn->prepare($request_sql);
    // $stmt->bind_param("issss", $user_id, $request_type, $membership, $requested_date, $status);
    // $stmt->execute();
    // $stmt->close();
    
    // // Display message for payment pending approval
    // $_SESSION['payment_pending'] = "Your renewal request is pending approval by the admin. Payment will be processed once approved.";
    header("Location: memb-payment.php?id={$user_id}");
    exit;
}

if ($action === 'upgrade') {
    // Check if an upgrade request already exists for this user
    $check_upgrade_sql = "SELECT req_userid, request_type FROM membership_requests WHERE req_userid = ? AND request_type = 'upgrade'";
    $stmt = $conn->prepare($check_upgrade_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $upgrade_exists = $stmt->num_rows > 0;
    $stmt->close();

    if ($upgrade_exists) {
        // If upgrade request already exists, redirect to the dashboard with a message
        $_SESSION['upgrade_req_exists'] = "You have already submitted a membership upgrade request.";
        header('Location: dashboard.php');
        exit;
    }

    // If no upgrade request exists, display the upgrade form
    echo "<h2> Choose Your New Membership Plan</h2>";
    echo "<form method='post' action='process_upgrade.php'>";
    echo "<input type='radio' name='membership' value='Silver' required> Silver<br>";
    echo "<input type='radio' name='membership' value='Gold'> Gold<br>";
    echo "<input type='radio' name='membership' value='Platinum'> Platinum<br>";
    echo "<button type='submit'>Confirm Upgrade</button>";
    echo "</form>";
}
else {
    echo "<p>Renewal is allowed only within 30 days of expiry.</p>";
}
?>
