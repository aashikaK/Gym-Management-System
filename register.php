<?php
include 'connection.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic server-side validation
    if (empty($username) || empty($email) || empty($password)) {
        $errorMsg = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'Invalid email format.';
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM users_login WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $sql);

        if (mysqli_num_rows($checkResult) > 0) {
            $errorMsg = 'Username already exists. Please choose another.';
        } else {
            // Insert valid data into the database
            $insertQuery = "INSERT INTO users_login (username, email, password) VALUES ('$username', '$email', '$password')";
            if (mysqli_query($conn, $insertQuery)) {
                $successMsg = 'Registration Successful!';
            } else {
                $errorMsg = 'Error during registration. Please try again.';
            }
        }
    }

    // Redirect back with error or success message
    header("Location: register.html?errorMsg=" . urlencode($errorMsg) . "&successMsg=" . urlencode($successMsg));
    exit();
}
?>
