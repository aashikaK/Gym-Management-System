<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require "connection.php";

$username = $_SESSION['username'];

// Fetch user details
$sql = "SELECT ul.username, ul.email, ui.phone_no 
        FROM users_login ul 
        JOIN users_info ui ON ul.id = ui.id 
        WHERE ul.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $current_username = $user['username'];
    $current_email = $user['email'];
    $current_phone_no = $user['phone_no'];
} else {
    $error_message = "User details not found.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_phone_no = trim($_POST['phone_no']);

    if (empty($new_username) || empty($new_email) || empty($new_phone_no)) {
        $error_message = "All fields are required.";
    } else {
        // Update the user's details
        $update_sql_login = "UPDATE users_login SET username = ?, email = ? WHERE username = ?";
        $update_sql_info = "UPDATE users_info SET phone_no = ? WHERE id = (SELECT id FROM users_login WHERE username = ?)";

        $update_stmt_login = $conn->prepare($update_sql_login);
        $update_stmt_info = $conn->prepare($update_sql_info);

        $conn->begin_transaction();
        try {
            $update_stmt_login->bind_param("sss", $new_username, $new_email, $username);
            $update_stmt_info->bind_param("ss", $new_phone_no, $username);

            $update_stmt_login->execute();
            $update_stmt_info->execute();

            $conn->commit();

            // Update session username and refresh data
            $_SESSION['username'] = $new_username;
            $current_username = $new_username;
            $current_email = $new_email;
            $current_phone_no = $new_phone_no;

            $success_message = "Profile updated successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Error updating profile. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Profile</h2>
        <?php if (isset($error_message)) { echo "<div class='message error'>$error_message</div>"; } ?>
        <?php if (isset($success_message)) { echo "<div class='message success'>$success_message</div>"; } ?>
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_username); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" required>

            <label for="phone_no">Phone Number</label>
            <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($current_phone_no); ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
