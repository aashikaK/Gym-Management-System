<?php
include 'connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $rent_price_per_month = $_POST['rent_price_per_month'];
    $purchase_price = $_POST['purchase_price'];
    $available_quantity = $_POST['available_quantity'];

    // Image Upload Handling
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "images/" . basename($image_name);

    if (move_uploaded_file($image_tmp, $image_path)) {
        $sql = "INSERT INTO equipment (name, rent_price_per_month, purchase_price, available_quantity, image_path) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sddis", $name, $rent_price_per_month, $purchase_price, $available_quantity, $image_path);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Equipment added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Equipment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            width: 400px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .submit-button {
            text-align: center;
            margin-top: 20px;
        }

        .submit-button button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-button button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Equipment</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Equipment Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="rent_price_per_month">Rent Price Per Month (₹):</label>
            <input type="number" id="rent_price_per_month" name="rent_price_per_month" step="0.01" required>

            <label for="purchase_price">Purchase Price (₹):</label>
            <input type="number" id="purchase_price" name="purchase_price" step="0.01" required>

            <label for="available_quantity">Available Quantity:</label>
            <input type="number" id="available_quantity" name="available_quantity" min="0" required>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" required>

            <div class="submit-button">
                <button type="submit">Add Equipment</button>
            </div>
        </form>
    </div>
</body>
</html>
