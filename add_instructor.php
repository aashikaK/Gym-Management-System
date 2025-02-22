<?php
include 'connection.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $experience = $_POST['experience'];
    $availability = $_POST['availability'];
    $phone = $_POST['phone']; // Fixed field name
    $email = $_POST['email'];
    $status = $_POST['status'];
    
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_url = "images/" . basename($image_name);
    
    if (move_uploaded_file($image_tmp, $image_url)) {
        $sql = "INSERT INTO instructors (name, specialty, experience, availability, phone_number, email, image_url, status) 
                VALUES ('$name', '$specialty', '$experience', '$availability', '$phone', '$email', '$image_url', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Instructor added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
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
    <title>Add Instructor</title>
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

        input[type="text"], input[type="number"], input[type="email"], input[type="file"] {
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
        <h2>Add Instructor</h2>
        <form action="" method="POST" enctype="multipart/form-data"> <!-- Fixed action -->
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" required>

            <label for="experience">Experience (years):</label>
            <input type="number" id="experience" name="experience" min="0" required>

            <label for="availability">Availability (1=Yes, 0=No):</label>
            <input type="number" id="availability" name="availability" min="0" max="1" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required> <!-- Fixed field name -->

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" required>

            <label for="status">Status:</label>
            <input type="text" id="status" name="status" required>

            <div class="submit-button">
                <button type="submit">Add Instructor</button>
            </div>
        </form>
    </div>
</body>
</html>
