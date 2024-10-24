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
                <form action="#">
                    <div class="field">
                        <input type="username" name="username" placeholder="Username ">
                    </div>
                    <div class="field">
                        <input type="password" name="" placeholder="Password ">
                        <i class='bx bx-hide icon-a'></i>
                    </div>
                    <div class="link">
                        <a href="" class="forget">Forgot password?</a>
                    </div>
                    <div class="button">
                        <button>Login</button>
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