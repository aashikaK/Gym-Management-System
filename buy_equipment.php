<?php
session_start();
require "connection.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$equipment_id = $_GET['id'];
$sql = "SELECT * FROM equipment WHERE id = $equipment_id";
$result = mysqli_query($conn, $sql);
$equipment = mysqli_fetch_assoc($result);

if (!$equipment) {
    echo "Equipment not found.";
    exit;
}

// Initialize variables for form
$total_price = 0;
$quantity = 1; // Default quantity

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission (Buy Now)
    $quantity = $_POST['quantity'];
    $location = $_POST['location'];
    $bank_account = $_POST['bank_account'];

    // Check if enough stock is available
    if ($quantity > $equipment['available_qty']) {
        $error_message = "Sorry, insufficient stock available.";
    } else {
        // Calculate total price
        $total_price = $equipment['discounted_amt'] * $quantity;

        // Update the quantity in the database
        $new_qty = $equipment['available_qty'] - $quantity;
        $update_qty_sql = "UPDATE equipment SET available_qty = $new_qty WHERE id = $equipment_id";
        $update_result = mysqli_query($conn, $update_qty_sql);

        if ($update_result) {
            // Show success message
            $success_message = "Thank you for your purchase!<br>";
            $success_message .= "You have bought {$quantity} unit(s) of {$equipment['name']}.<br>";
            $success_message .= "Total Price: Rs {$total_price}<br>";
            $success_message .= "Delivery Location: {$location}<br>";
            $success_message .= "Bank Account: {$bank_account}<br>";
            $success_message .= "Your equipment will be delivered soon. <strong>Free Delivery!</strong>";
        } else {
            $error_message = "There was an error updating the equipment quantity. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Equipment - <?php echo htmlspecialchars($equipment['name']); ?></title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<style>
    /* (Same styles as your existing purchase page) */
</style>
<body>
    <?php if (isset($success_message)): ?>
        <div class="success-message">
            <?php echo $success_message; ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <div class="purchase-container">
        <h2>Buy <?php echo htmlspecialchars($equipment['name']); ?></h2>

        <form action="" method="POST">
            <input type="hidden" name="equipment_id" value="<?php echo $equipment['id']; ?>">

            <label for="discounted_amt">Discounted Price (per unit):</label>
            <input type="text" id="discounted_amt" value="Rs <?php echo number_format($equipment['discounted_amt'], 2); ?>" readonly>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $equipment['available_qty']; ?>" value="1" onchange="calculateTotal()">

            <label for="location">Delivery Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="bank_account">Bank Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" required>

            <div class="total-price" id="total_price">Total: Rs <?php echo number_format($equipment['discounted_amt'], 2); ?></div>

            <div class="free-delivery">Free Delivery</div>

            <div class="buy-button">
                <button type="submit">Confirm Purchase</button>
            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            const amount = <?php echo $equipment['discounted_amt']; ?>;
            const quantity = document.getElementById('quantity').value;
            const total = amount * quantity;
            document.getElementById('total_price').textContent = 'Total: Rs ' + total.toFixed(2);
        }
    </script>
</body>
</html>
