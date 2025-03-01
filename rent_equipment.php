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

$rental_price = 0;
$quantity = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $rental_start_date = $_POST['start_date'];
    $rental_end_date = $_POST['end_date'];
    $bank_account = $_POST['bank_account'];

    $months = (strtotime($rental_end_date) - strtotime($rental_start_date)) / (30 * 24 * 60 * 60);
    $rental_price = $equipment['rent_price_per_month'] * $months * $quantity;

    // Fetch available rental quantity from `rental_equipments`
    $rent_equip_query = "SELECT available_rental_qty FROM rental_equipments WHERE equipment_id = $equipment_id";
    $rent_equip_result = mysqli_query($conn, $rent_equip_query);
    $rental_equipment = mysqli_fetch_assoc($rent_equip_result);

    if ($quantity > $rental_equipment['available_rental_qty']) {
        $error_message = "Sorry, insufficient stock available.";
    } else {
        // $new_quantity = $rental_equipment['available_rental_qty'] - $quantity;

        // Update available quantity in `pending_rental`

        $requested_date = date('Y-m-d H:i:s');
        $rent_approv_sql = "INSERT INTO pending_rental(e_id, user_id,requested_qty, requested_date, status, approved_date) VALUES ('$equipment_id', '{$_SESSION['id']}','$quantity', '$requested_date', 'pending', NULL);";
        // Insert rental transaction into `rental_transactions`
        $insert_rental_sql = "
            INSERT INTO rental_transactions (rental_id, user_id, rental_date, due_date, is_returned) 
            VALUES ('$equipment_id', '{$_SESSION['id']}', '$rental_start_date', '$rental_end_date', 0)";
        
        $update_result = mysqli_query($conn, $rent_approv_sql);
        $insert_result = mysqli_query($conn, $insert_rental_sql);

        if ($update_result ) {
            $success_message = "Rental request is submitted for approval!<br>
                Equipment: {$equipment['name']}<br>
                Quantity: $quantity<br>
                Total Rental Price: Rs {$rental_price}<br>
                Rental Period: $rental_start_date to $rental_end_date<br>
                Your rented equipment will be delivered soon after approved by the admin.";
        } else {
            $error_message = "Error processing rental. Please try again.";
        }
    }
}
?>

<!-- HTML content with form and success/error messages here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Equipment - <?php echo htmlspecialchars($equipment['name']); ?></title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<style>
    /* Styling similar to buy equipment form */
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
    .rental-container { width: 400px; margin: auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
    .rental-container h2 { text-align: center; color: #FFD700; margin-bottom: 20px; }
    label { display: block; width: 100%; margin-top: 10px; color:#218838; }
     input { display: block; width: 100%; margin-top: 10px;  padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
    .rent-button button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; }
    .rent-button button:hover { background-color: #218838; }
    .error-message { color: red; }
    .success-message { color: green; }
</style>

<body>
    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="rental-container">
        <h2>Rent <?php echo htmlspecialchars($equipment['name']); ?></h2>
        <form action="" method="POST">
            <input type="hidden" name="equipment_id" value="<?php echo $equipment['equipment_id']; ?>">

            <label for="rent_price">Monthly Rent Price:</label>
            <input type="text" id="rent_price" value="Rs <?php echo number_format($equipment['rent_price_per_month'], 2); ?>" readonly>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $equipment['available_quantity']; ?>" value="1">

            <label for="start_date">Rental Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Rental End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="bank_account">Bank Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" required>

            <div class="rent-button">
                <button type="submit">Confirm Rental</button>
            </div>
        </form>
    </div>
</body>
</html>
