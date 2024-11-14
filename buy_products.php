<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<style>
    h2{
        
    color:#FFD700;
    font-size:2.3rem;
    text-align:center;
    padding:40px;
    }
    .product-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    row-gap:80px;
}
.product {
    border: 1px solid #ddd;
    padding: 20px;
    width: 320px;
    height:auto;
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
    color:#FFD700;
}
.product span{
    color:red;
}
.product p {
    font-size: 1em;
    color: #ffff;
    padding-bottom: 9px;
}

.buy-button {
    text-decoration: none;
    padding: 8px 16px;
    background-color: #28a745;
    color: white;
    border-radius: 5px;
    font-weight: bold;
}
.buy-button:hover {
    background-color: #218838;
}

</style>
<body>
    <h2>Our Products</h2>
    <div class="product-container">
        <?php
        // Check if there are products in the database
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Display each product with a Buy Now button
                echo '<div class="product">';
                echo '<img src=" '.htmlspecialchars($row['prod_images']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>Market Price: Rs ' . number_format($row['MRP'], 2) . '</p>';
                echo '<p>Discounted price: Rs ' . number_format($row['discounted_amt'], 2) . '</p>';
                echo '<a href="buy_product.php?id=' . $row['id'] . '" class="buy-button">Buy Now</a>';
                if($row['available_qty']==0){
                    echo'<span>This product is not available</span>';
                }
                echo '</div>';
                
            }
        
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>
</body>
</html>
