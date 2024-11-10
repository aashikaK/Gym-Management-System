<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];
$action = $_GET['action'] ?? '';

// Fetch user info
$sql = "SELECT membership, membership_expiry_date FROM users_info WHERE id = (SELECT id FROM users_login WHERE username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($membership, $membership_expiry_date);
$stmt->fetch();
$stmt->close();

// Check if renewal is allowed (e.g., within 30 days of expiry)
$allow_renewal = (new DateTime($membership_expiry_date))->diff(new DateTime())->days <= 30;

if ($action === 'renew' && $allow_renewal) {
    // Logic for renewing membership
    // For example, extend expiry date by a year and update the amount_due

    // Example renewal SQL query
    $new_expiry_date = (new DateTime($membership_expiry_date))->modify('+1 year')->format('Y-m-d');
    $renewal_sql = "UPDATE users_info SET membership_expiry_date = ?, amount_due = ? WHERE id = (SELECT id FROM users_login WHERE username = ?)";
    $stmt = $conn->prepare($renewal_sql);
    $new_amount_due = 100; // Replace with actual renewal fee
    $stmt->bind_param("sds", $new_expiry_date, $new_amount_due, $username);
    $stmt->execute();
    $_SESSION['payment_success'] = "Membership successfully renewed!";
    header("Location: dashboard.php#membership");
    exit;

} elseif ($action === 'upgrade') {
    // Logic for upgrading membership
    // Redirect to an upgrade options page or display upgrade options here
    echo "<h2> Choose Your New Membership Plan</h2>";
    echo "<form method='post' action='process_upgrade.php'>";
    echo "<input type='radio' name='membership' value='Silver' required> Silver<br>";
    echo "<input type='radio' name='membership' value='Gold'> Gold<br>";
    echo "<input type='radio' name='membership' value='Platinum'> Platinum<br>";
    echo "<button type='submit'>Confirm Upgrade</button>";
    echo "</form>";
} else {
    echo "<p>Renewal is allowed only within 30 days of expiry.</p>";
}
?>
