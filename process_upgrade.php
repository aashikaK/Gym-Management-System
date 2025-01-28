<?php
session_start();
require "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT id FROM users_login WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Get the selected membership type from the form
$membership_type = $_POST['membership'];
$current_date = new DateTime();
$request_date = $current_date->format('Y-m-d H:i:s');

// Insert the upgrade request into the database
$sql = "INSERT INTO membership_requests (req_userid, request_type, requested_membership_type, requested_date, status) 
        VALUES (?, 'upgrade', ?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $membership_type, $request_date);

if ($stmt->execute()) {
    $_SESSION['mem_payment_success'] = "Your membership upgrade request has been successfully submitted!";
    header('Location: dashboard.php');
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
