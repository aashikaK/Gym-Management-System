<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$equipment_id = intval($_GET['id']);
$user_id = $_SESSION['id'];

// Check if a return request has already been submitted
$return_check_sql = "SELECT COUNT(*) AS return_count 
                     FROM pending_rental_return 
                     WHERE e_id = ? AND user_id = ? AND status = 'pending'";
$stmt = $conn->prepare($return_check_sql);
$stmt->bind_param("ii", $equipment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['return_count'] > 0) {
    echo "<h2>Return Request Already Submitted</h2>";
    echo '<p>You have already submitted a return request for this equipment. Please wait for it to be processed.</p>';
} else {
    // Check if the equipment is rented and not already returned
    $rented_check_sql = "SELECT COUNT(*) AS rented_count 
                         FROM rental_transactions WHERE user_id='$user_id' AND rental_id='$equipment_id' AND is_returned = 0";
    $stmt = $conn->prepare($rented_check_sql);
   
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['rented_count'] > 0) {
        echo "<h2>Return Process Initiated</h2>";
        echo '<p>Your equipment return request has been processed for approval successfully!</p>';

        $requested_date = date('Y-m-d H:i:s');
        $return_sql = "INSERT INTO pending_rental_return(e_id, user_id, requested_return_date, status, approved_return_date) 
                       VALUES (?, ?, ?, 'pending', NULL)";
        $stmt = $conn->prepare($return_sql);
        $stmt->bind_param("iis", $equipment_id, $user_id, $requested_date);
        $stmt->execute();
    } else {
        echo "<h2>This product has not been rented yet.</h2>";
        echo '<a href="rent_buy_equipment.php">Go Back</a>'; 
    }
}

$stmt->close();
$conn->close();
?>
