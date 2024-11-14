<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];

// Fetch equipment from the database
$sql = "SELECT * FROM equipment";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent or Buy Equipment - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<style>
    h2 {
        color: #FFD700;
        font-size: 2.3rem;
        text-align: center;
        padding: 40px;
    }
    .product-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        row-gap: 80px;
    }
    .product {
        border: 1px solid #ddd;
        padding: 20px;
        width: 320px;
        height: auto;
        text-align: center;
        border-radius: 8px;
    }
    .product img {
        width: 200px;
        height: auto;
        border-radius: 8px;
    }
    .product h3 {
        font-size: 1.2em;
        margin: 10px 0;
        color: #FFD700;
    }
    .product span {
        color: red;
    }
    .product p {
        font-size: 1em;
        color: #ffff;
        padding-bottom: 9px;
    }
    .buy-button, .rent-button {
        text-decoration: none;
        padding: 8px 16px;
        background-color: #28a745;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        display: inline-block;
        margin: 10px;
    }
    .buy-button:hover, .rent-button:hover {
        background-color: #218838;
    }
</style>
<body>
    <h2>Our Equipments</h2>
    <div class="product-container">
        <?php
        // Check if there is equipment in the database
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Display each equipment item with Buy and Rent options
                echo '<div class="product">';
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>Monthly Rent Price: Rs ' . number_format($row['rent_price_per_month'], 2) . '</p>';
                echo '<p>Purchase Price: Rs ' . number_format($row['purchase_price'], 2) . '</p>';
                echo '<a href="buy_equipment.php?id=' . $row['equipment_id'] . '" class="buy-button">Buy Now</a>';
                if($row['purchase_price']<10000){
                    echo '<span> <br> This equipment is not available for renting <span>';
                } else{
                echo '<a href="rent_equipment.php?id=' . $row['equipment_id'] . '" class="rent-button">Rent Now</a>';
                }
                if ($row['available_quantity'] == 0) {
                    echo '<span>This equipment is not available</span>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>No equipment available.</p>';
        }
        ?>
    </div>
</body>
</html>
