<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } else {
        $username = $_SESSION['username'];

        // Fetch the user's current password
        $sql = "SELECT password FROM users_login WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($old_password=== $row['password']) {
                if ($new_password === $old_password) {
                    $error_message = "New password cannot be the same as the old password.";
                } elseif ($new_password === $confirm_password) {
                    // Update the password
                    // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                     $update_sql = "UPDATE users_login SET password = ? WHERE username = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_password, $username);

                    if ($update_stmt->execute()) {
                        $success_message = "Password changed successfully.";
                    } else {
                        $error_message = "Error updating password. Please try again.";
                    }
                } else {
                    $error_message = "New password and confirm password do not match.";
                }
            } else {
                $error_message = "Old password is incorrect.";
            }
        } else {
            $error_message = "User not found.";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color:  #2ecc71;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error-message, .success-message {
            color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .error-message {
            color: #e74c3c;
        }
        .success-message {
            color: #2ecc71;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Change Password</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"> <?php echo $error_message; ?> </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="success-message"> <?php echo $success_message; ?> </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" name="old_password" >

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" >

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" >

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
