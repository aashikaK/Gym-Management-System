<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

require "connection.php";
$username = $_SESSION['username'];
$email = $_SESSION['email'];
// Fetch user and payment info
$sql = "
    SELECT ui.phone_no, ui.membership, ui.membership_expiry_date, ui.bmi, ui.amount_due, ui.due_date, ui.status
    FROM users_info ui 
    JOIN users_login ul ON ui.id = ul.id 
    WHERE ul.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($phonenumber,$membership, $membership_expiry_date, $bmi, $amount_due, $due_date, $status);
$stmt->fetch();
$stmt->close();

// Calculate remaining days until payment due date or expiry date
$current_date = new DateTime();
$expiry = new DateTime($due_date);
$remaining_days = ($expiry > $current_date) ? $expiry->diff($current_date)->days : 0;

$payment_message = ($status === 'Unpaid' && $remaining_days == 0) ? 'Payment is left to pay' : 'Your payment is up to date';


$sql="SELECT  ui."

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <section class="header">
        <!-- Left Section with Title and Logo -->
        <div class="left">
            <h1 class="title">
                <img class="logo" src="images/logo.jpg" alt="ShapeShifter Fitness and Gym logo">
                ShapeShifter Fitness Dashboard
            </h1>
        </div>

        <!-- Navigation Bar -->
        <nav class="nav-bar">
            <a href="#overview">Overview</a>
            <a href="#membership">Membership</a>
            <a href="#workouts">Workouts</a>
            <a href="equipment_products">Equipment and Products</a>
            <a href="#payment">Make Payment</a>
            <a href="#profile">Profile</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>

        <!-- Search Form -->
        <div class="right">
            <form class="search" method="post">
                <input type="search" name="query" placeholder="Search..." aria-label="Search">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </section>


    <!-- Dashboard Overview Section -->
    <section class="dashboard-overview" id="overview">
        <h2>Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?> </h2>
        <p>Here's a quick overview of your fitness journey at ShapeShifter Fitness.</p>
        <div class="stats">
            <div class="stat-item">
                <h3>Current Membership</h3>
                <p><?php echo htmlspecialchars($membership); ?> - Expires: <?php echo htmlspecialchars($membership_expiry_date); ?></p>
             </div>
             <div class="stat-item">
                <h3>Workout Plans</h3>
                <p>Click to view and update your workout plans based on BMI.</p>
                <a href="workout_dashboard.php" class="cta-button" style="text-decoration: none;">Explore Workouts</a>
             </div>

            <!--BMI SECTION-->
            <div class="stat-item">
                <h3>BMI Calculation</h3>
                <p>Current BMI: <?php echo htmlspecialchars($bmi); ?> </p>
                <a href="bmi_calculator.html" target="_blank" class="cta-button" style="text-decoration:none;">Calculate BMI</a>
            </div>
        </div>
    </section>

    <!-- Membership Section -->
<section class="dashboard-section" id="membership">
    <h2>Membership Details</h2>
    <p>Manage your membership plan, upgrade options, and view exclusive offers.</p>
    <a href="membership_action.php?action=renew" class="cta-button" style="text-decoration:none;">Renew Membership</a>
    <a href="membership_action.php?action=upgrade" class="cta-button" style="text-decoration:none;">Upgrade Plan</a>
</section>



    <!-- Workout Section -->
    <section class="dashboard-section" id="workouts">
    <h2>Workout Programs</h2>
    <p>Access your personalized workout plans and track progress. As well as choose the trainers for you.</p>
    <a href="explore_workouts_instructors.php" class="cta-button" style="text-decoration:none;">Explore Our Workouts and Instructors</a>
        
    </div>
</section>


<!-- Equipment and products section -->
<!-- Equipment and Products Section -->
<section class="dashboard-section" id="equipment-products">
    <h2>Equipment and Products</h2>
    <p>Browse our selection of gym equipment and fitness products available for purchase and rental.</p>
    
    <div class="equipment-products-actions">
        <!-- Button to Buy Products -->
        <a href="buy_products.php" class="cta-button" style="text-decoration:none;">Buy Products</a>
        
        <!-- Button to Rent and Buy Equipment -->
        <a href="rent_buy_equipment.php" class="cta-button" style="text-decoration:none;">Rent/Buy Equipment</a>
    </div>
</section>




    <!-- Make Payment Section -->
    <section class="payment-section" id="payment">
    <h2>Make Payment</h2>
    <div class="payment-details">
        <p>Amount Due: Rs <?php echo number_format($amount_due, 2); ?></p>
        <p>Expiry Date: <?php echo htmlspecialchars($due_date); ?></p>
        <p>Remaining Days: <?php echo $remaining_days; ?> days</p>
        <p>Status: <?php echo htmlspecialchars($status); ?></p>

        <?php if ($status === 'Unpaid' && $remaining_days == 0) { ?>
    <a href="pay_now.php" style="text-decoration: none;" class="cta-button">Pay Now</a>
<?php } else { ?>
    <p><?php echo $payment_message; ?></p>
<?php } ?>
    </div>

    <!-- success message -->
   <?php
if (isset($_SESSION['payment_success'])) {
    echo "<p class='success-message'>" . $_SESSION['payment_success'] . "</p>";
    unset($_SESSION['payment_success']);
}
?>
</section>

    <!-- Profile Section -->
    <section class="dashboard-section" id="profile">
        <h2>Your Profile</h2>
        <p>Update your personal details and preferences.</p>
        <div class="profile-details">
            <p>Name: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p>Email: <?php echo htmlspecialchars($_SESSION['email']);?> </p>
            <p>Phone: <?php echo htmlspecialchars($phonenumber);?></p>
            <button class="cta-button">Edit Profile</button>
            <button class="cta-button">Change Password</button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ShapeShifter Fitness and Gym. All rights reserved.</p>
        <div class="social-media">
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
        </div>
    </footer>
</body>
</html>



<?php

