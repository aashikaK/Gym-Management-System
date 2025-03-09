<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";

$username = $_SESSION['username'];
$user_id = $_SESSION['id'];

// Retrieve user details including payment_due_date and payment_due
$sql = "SELECT * FROM users_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $payment_due_date = $row['payment_due_date'];
    $payment_due = $row['payment_due'];
}
$stmt->close();

// Calculate the updated payment_due if the due date has passed
$current_date = new DateTime();
$due_date = $payment_due_date ? new DateTime($payment_due_date) : null;
$monthly_fee = 1000;
$due_date_passed = false;

if ($due_date && $due_date < $current_date) {
    // Calculate the number of months overdue
    $interval = $due_date->diff($current_date);
    $months_overdue = $interval->m + ($interval->y * 12);

    // If overdue by even one day, count it as one full month
    if ($interval->d > 0 || $months_overdue == 0) {
        $months_overdue += 1;
    }

    // Update payment due
    $payment_due = $monthly_fee * $months_overdue;

    // Update the database with the new amount_due
    $sql = "UPDATE users_info SET payment_due = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $payment_due, $user_id);
    $stmt->execute();
    $stmt->close();

    $due_date_passed = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the payment
    $account_number = $_POST['account_number'];
    $payment_status = "Pending";

    // Ensure $payment_due is properly initialized
    $amount_paid = isset($payment_due) ? $payment_due : 0; // Default to 0 if null

    // Insert payment request into the pending_payment table
    $req_userid = $_SESSION['id'];
    $requested_date = date('Y-m-d H:i:s');

    $admin_approv_sql = "INSERT INTO pending_payment (req_userid, amount_paid, requested_date, status, approved_date) 
                          VALUES ('$req_userid','$amount_paid' ,'$requested_date' ,'$payment_status', NULL)";
    $stmt = $conn->prepare($admin_approv_sql);
    $stmt->execute();
    $stmt->close();

    // Fix SQL syntax error in pay_sql
    $pay_sql= "INSERT INTO system_payment (user_id, amount, status) 
               VALUES ($req_userid,$amount_paid, 'pending')";
    mysqli_query($conn, $pay_sql);

    // Redirect back to dashboard with success message
    $_SESSION['payment_success'] = "Payment will be processed! Payment due date will be extended by 30 days after being processed.";
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
        <?php if ($due_date_passed): ?>
            <form action="pay_now.php" method="POST">
                <label for="amount">Amount Due (Rs):</label>
                <input type="text" id="amount" name="amount" value="<?php echo number_format($payment_due, 2); ?>" readonly>
                
                <label for="bank_account">Bank Account Number:</label>
<input type="text" id="account_number" name="account_number" required 
       pattern="^(?=.*\d)[A-Za-z0-9]+$" 
       title="Bank account number must contain at least one number and may include letters, but cannot be only letters.">

                <button type="submit" class="cta-button">Submit Payment</button>
            </form>
        <?php else: ?>
            <p>No payment is required currently. Your due date is <?php echo $payment_due_date; ?>.</p>
        <?php endif; ?>
    </section>
</body>
</html>
