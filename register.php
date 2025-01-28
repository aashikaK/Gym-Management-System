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
        $sql = "SELECT * FROM users_login WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $username);  // Bind the username to the prepared statement
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Username already exists
                $errorMsg = 'Username already exists. Please choose another.';
            } else {
                // Insert valid data into the users_login table
                $insertQuery = "INSERT INTO users_login (username, email, password) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($insertQuery)) {
                    $stmt->bind_param('sss', $username, $email, $password);  // Bind the form data
                    if ($stmt->execute()) {
                        // Get the last inserted user ID
                        $user_id = $conn->insert_id;

                        // Now insert the user ID into the users_info table as a foreign key
                        $insertInUsersInfo = "INSERT INTO users_info (id, payment_due) VALUES (?, 1000)";
                        if ($stmt = $conn->prepare($insertInUsersInfo)) {
                            $stmt->bind_param('i', $user_id);  // Bind the user ID to the prepared statement
                            if ($stmt->execute()) {
                                $successMsg = 'Registration Successful!';
                            } else {
                                $errorMsg = 'Error inserting into users_info table. Please try again.';
                            }
                        }
                        else {
                        $errorMsg = 'Error during registration. Please try again.';
                    }
                }
            }

            $stmt->close();  // Close the prepared statement
        }
    }

    // Redirect back with error or success message
    header("Location: register.html?errorMsg=" . urlencode($errorMsg) . "&successMsg=" . urlencode($successMsg));
    exit();
}
?>
