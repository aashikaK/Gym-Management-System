<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];
$new_membership = $_POST['membership'] ?? '';

// Update the user's membership level
if ($new_membership) {
    $update_sql = "UPDATE users_info SET membership = ? WHERE id = (SELECT id FROM users_login WHERE username = ?)";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $new_membership, $username);
    $stmt->execute();
    $_SESSION['payment_success'] = "Membership upgraded to $new_membership!";
    header("Location: dashboard.php#membership");
    exit;
} else {
    echo "<p>Error: No membership level selected for upgrade.</p>";
}
?>

