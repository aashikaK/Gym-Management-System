<?php
session_start();
require "connection.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$equipment_id = $_GET['id'];
$sql = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
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
    if ($quantity > $equipment['available_quantity']) {
        $error_message = "Sorry, insufficient stock available.";
    } else {
        // Calculate total price
        $total_price = $equipment['purchase_price'] * $quantity;

        $req_userid = $_SESSION['id']; // Get the logged-in user's ID
        $requested_date = date('Y-m-d H:i:s'); // Get the current date and time
        $admin_approv_sql = "INSERT INTO pending_equipment(e_id, req_userid,requested_qty, requested_date, status, approved_date) 
        VALUES($equipment_id, $req_userid,$quantity, '$requested_date', 'pending', NULL)";
        
 $update_result = mysqli_query($conn, $admin_approv_sql);

        if ($update_result) {
            // Show success message
            $success_message = "Your request to purchase {$quantity} unit(s) of {$equipment['name']} has been submitted.<br>";
            $success_message .= "Total Price: Rs {$total_price}<br>";
            $success_message .= "Delivery Location: {$location}<br>";
            $success_message .= "Bank Account: {$bank_account}<br>";
            $success_message .= "Please wait while the request is being processed.";
             } else {
            $error_message = "There was an error updating the equipment quantity. Please try again.";
        }
        $pay_sql = "INSERT INTO eqp_payment (user_id,e_id, amount, status) 
        VALUES ('$req_userid','$equipment_id', $total_price, 'pending')";

        mysqli_query($conn, $pay_sql);

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
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .purchase-container {
        width: 400px;
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .purchase-container h2 {
        text-align: center;
        color: #FFD700;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input[type="text"], input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .total-price {
        color: #28a745;
        font-weight: bold;
        margin-top: 5px;
    }

    .buy-button {
        text-align: center;
        margin-top: 20px;
    }

    .buy-button button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .buy-button button:hover {
        background-color: #218838;
    }

    .free-delivery {
        color: blue;
        font-size: 0.9rem;
        text-align: center;
        margin-top: 10px;
    }

    .error-message { 
        color: red; 
    }

    .success-message { 
        color: green; 
    }
    a{
            text-decoration:none;
            background-color:green;
            border-radius:4%;
            color:white;
            font-size:15px;
                }
              p{
            color:black;
            font-size:15px;
                }
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
    <p>Click <a href="dashboard.php">here to go back to dashboard.</a> </p>
        <h2>Buy <?php echo htmlspecialchars($equipment['name']); ?></h2>

        <form action="" method="POST">
            <input type="hidden" name="equipment_id" value="<?php echo $equipment['equipment_id']; ?>">

            <label for="purchase_price">Purchase Price (per unit):</label>
            <input type="text" id="purchase_price" value="Rs <?php echo number_format($equipment['purchase_price'], 2); ?>" readonly>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $equipment['available_quantity']; ?>" value="1" onchange="calculateTotal()">

            <label for="location">Delivery Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="bank_account">Bank Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" required pattern="^(?=.*\d)[A-Za-z0-9]+$" 
            title="Bank account number must contain at least one number and may include letters, but cannot be only letters.">

            <div class="total-price" id="total_price">Total: Rs <?php echo number_format($equipment['purchase_price'], 2); ?></div>

            <div class="free-delivery">Free Delivery</div>

            <div class="buy-button">
                <button type="submit">Confirm Purchase</button>
            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            const amount = <?php echo $equipment['purchase_price']; ?>;
            const quantity = document.getElementById('quantity').value;
            const total = amount * quantity;
            document.getElementById('total_price').textContent = 'Total: Rs ' + total.toFixed(2);
        }
    </script>
</body>
</html>
