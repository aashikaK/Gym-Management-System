<?php
session_start();
require "connection.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found.";
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
    if ($quantity > $product['available_qty']) {
        $error_message = "Sorry, insufficient stock available.";
    } else {
        // Calculate total price
        $total_price = $product['discounted_amt'] * $quantity;

        // Update the quantity in the database
        $new_qty = $product['available_qty'] - $quantity;
        $update_qty_sql = "UPDATE products SET available_qty = $new_qty WHERE id = $product_id";
        $update_result = mysqli_query($conn, $update_qty_sql);

        if ($update_result) {
            // Show success message
            $success_message = "Thank you for your purchase!<br>";
            $success_message .= "You have bought {$quantity} unit(s) of {$product['name']}.<br>";
            $success_message .= "Total Price: Rs {$total_price}<br>";
            $success_message .= "Delivery Location: {$location}<br>";
            $success_message .= "Bank Account: {$bank_account}<br>";
            $success_message .= "Your product will be delivered soon. <strong>Free Delivery!</strong>";
        } else {
            $error_message = "There was an error updating the product quantity. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Product - <?php echo htmlspecialchars($product['name']); ?></title>
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
    </style>
<body>
    <!-- Display Success/Error Message -->
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
        <h2>Buy <?php echo htmlspecialchars($product['name']); ?></h2>

        <form action="" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

            <label for="discounted_amt">Discounted Price (per unit):</label>
            <input type="text" id="discounted_amt" value="Rs <?php echo number_format($product['discounted_amt'], 2); ?>" readonly>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['available_qty']; ?>" value="1" onchange="calculateTotal()">

            <label for="location">Delivery Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="bank_account">Bank Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" required>

            <div class="total-price" id="total_price">Total: Rs <?php echo number_format($product['discounted_amt'], 2); ?></div>

            <div class="free-delivery">Free Delivery</div>

            <div class="buy-button">
                <button type="submit">Confirm Purchase</button>
            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            const amount = <?php echo $product['discounted_amt']; ?>;
            const quantity = document.getElementById('quantity').value;
            const total = amount * quantity;
            document.getElementById('total_price').textContent = 'Total: Rs ' + total.toFixed(2);
        }
    </script>
</body>
</html>