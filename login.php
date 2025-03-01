   <!-- Php for the form -->
   <?php
session_start();

require 'connection.php';

if(isset($_POST['login'])){
    $username= $_POST['username'];
    $password=$_POST['password'];
    
if (!empty($username) && !empty($password)) {
    // Check if the user is an admin
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
       
    $queryresult = mysqli_query($conn, $query);
    if (mysqli_num_rows($queryresult) > 0) {
        $_SESSION['admin_logged_in']=1;
        header("Location: admin-panel.php");
        exit();
    }
    // Check if the user is a regular user
    $sql = "SELECT * FROM users_login WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $row['email'];
        $_SESSION['id'] = $row['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "Invalid username and password. Make sure you type the correct username and password.";
    }
} else {
    $error_msg = "Please enter both username and password.";
}

 } 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-login.css">
    <script src="./login-script.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>

<body>
    <section class="container forms">
        <div class="form">
            <div class="form-content">
                <header>Login</header>
                <p style="color: red;"><?php echo isset($error_msg) ? $error_msg : ''; ?></p>

                <form action="" method="post">
                    <div class="field">
                        <input type="text" name="username" placeholder="Username">
                    </div>
                    <div class="field">
    <input type="password" name="password" placeholder="Password" id="password">
    <i class="bx bx-hide icon-a"></i>
</div>


                    <div class="link">
                        <a href="forgot-password.html" class="forget">Forgot password?</a>
                    </div>
                    <div class="button">
                        <button type="submit" name="login">Login</button>
                    </div>
                </form>
            </div>
            <div class="line"></div>
            <div class="icon">
                <div id="g_id_onload"
                     data-client_id="YOUR_GOOGLE_CLIENT_ID"
                     data-callback="handleCredentialResponse">
                </div>
                <div class="g_id_signin" data-type="standard"></div>
            </div>
        </div>
    </section>
</body>
</html>