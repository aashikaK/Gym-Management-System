<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];

// Retrieve the current amount_due only once
$sql = "SELECT amount_due FROM users_info ui JOIN users_login ul ON ui.id = ul.id WHERE ul.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($amount_due);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the payment
    $amount = $_POST['amount'];
    $account_number = $_POST['account_number'];
    $payment_status = "Paid";

    // Update payment status and due date
    $new_due_date = (new DateTime())->modify('+30 days')->format('Y-m-d');
    $sql = "
        UPDATE users_info 
        SET status = ?, due_date = ?, amount_due = 0 
        WHERE id = (SELECT id FROM users_login WHERE username = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $payment_status, $new_due_date, $username);
    $stmt->execute();
    $stmt->close();

    // Redirect back to dashboard with success message
    $_SESSION['payment_success'] = "Payment successful! Due date extended by 30 days.";
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Now - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>
    <section class="payment-form-section">
        <h2>Complete Your Payment</h2>
        <form action="pay_now.php" method="POST">
            <label for="amount">Amount Due (Rs):</label>
            <input type="text" id="amount" name="amount" value="<?php echo number_format($amount_due, 2); ?>" readonly>
            
            <label for="account_number">Bank Account Number:</label>
            <input type="text" id="account_number" name="account_number" required>
            
            <button type="submit" class="cta-button">Submit Payment</button>
        </form>
    </section>
</body>
</html>
