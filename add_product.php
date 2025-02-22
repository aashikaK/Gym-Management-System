<?php
include 'connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mrp = $_POST['MRP'];
    $discounted_amt = $_POST['discounted_amt'];
    $available_qty = $_POST['available_qty'];

    // Image Upload Handling
    $image_name = $_FILES['prod_image']['name'];
    $image_tmp = $_FILES['prod_image']['tmp_name'];
    $image_url = "images/" . basename($image_name);

    if (move_uploaded_file($image_tmp, $image_url)) {
        $sql = "INSERT INTO products (name, MRP, discounted_amt, available_qty, prod_images) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sddis", $name, $mrp, $discounted_amt, $available_qty, $image_url);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Product added successfully.";
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
    <title>Add Product</title>
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
        <h2>Add Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="MRP">MRP (₹):</label>
            <input type="number" id="MRP" name="MRP" step="0.01" required>

            <label for="discounted_amt">Discounted Price (₹):</label>
            <input type="number" id="discounted_amt" name="discounted_amt" step="0.01" required>

            <label for="available_qty">Available Quantity:</label>
            <input type="number" id="available_qty" name="available_qty" min="0" required>

            <label for="prod_image">Upload Image:</label>
            <input type="file" id="prod_image" name="prod_image" required>

            <div class="submit-button">
                <button type="submit">Add Product</button>
            </div>
        </form>
    </div>
</body>
</html>
