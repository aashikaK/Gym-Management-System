   <!-- Php for the form -->
   <?php
session_start();

require 'connection.php';




if(isset($_POST['login'])){
    $username= $_POST['username'];
    $password=$_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users_login WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['email']= $row['email'];
            $_SESSION['id']= $row['id'];
            header("Location: dashboard.php");
            exit();
         } 
       else {
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
</head>
<body>
    <section class="container forms">
        <div class="form">
            <div class="form-content">
                <header>Login</header>

                <!-- PHP FOR ERROR MESSAGE -->

                <!-- PHP FOR ERROR MESSAGE -->
<?php if(!empty($error_msg)) { ?>
    <p style="color:red; text-align: center;"><?php echo $error_msg; ?></p>
<?php } ?>


                <!-- form -->

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="field">
                        <input type="text" name="username" placeholder="Username ">
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password ">
                        <i class='bx bx-hide icon-a'></i>
                    </div>
                    <div class="link">
                        <a href="" class="forget">Forgot password?</a>
                    </div>
                    <div class="button">
                        <button name="login" value="login">Login</button>
                    </div>

                </form>
            </div>
            <div class="line"></div>
            <div class="icon">
                <a href="" class="google">
                    <img src="./images/google.jpg" alt="" class="google-img">
                    <span>Login with google</span>
                </a>
            </div>
        </div>
    </section>
</body>
<script src="login-script.js"></script>
</html>