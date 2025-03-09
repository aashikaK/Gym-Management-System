<?php
// Start the session
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="left">
            <h1 class="title">
                <img class="logo" src="images/logo.jpg" alt="ShapeShifter Fitness and Gym logo">
                Admin Panel
            </h1>
        </div>
        <nav class="nav-bar">
            <a href="#requests">Pending Requests</a>
            <a href="#instructors">Instructors and Workouts</a>
            <a href="#equipment-products">Equipment & Products</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </header>

    <!-- Pending Requests Section --><?php
   require 'pending-prod.php'; ?>

        <!-- For instructor -->
         <?php
      require 'pending-instr.php' ?>
  
  <!-- for membership -->
<?php
  require 'pending-memb.php';  ?>


<!-- EQUIPMENT BUY REQUEST APPROVAL OR REJCTION -->
 <?php
require 'pending-eqp.php'; ?>

            <!-- For rental equipment -->
           <?php
           require 'pending-rental.php'; ?>


<!-- for rental return equipment -->
<?php
require 'pend-rentalret.php';
?>

<!-- Payment approval or rejection -->
<?php
require 'pend-payment.php';
?>

<?php
// Check if there are any pending requests in both tables
$productPendingQuery = "SELECT COUNT(*) AS product_count FROM pending_product WHERE status = 'pending'";
$instructorPendingQuery = "SELECT COUNT(*) AS instructor_count FROM instructor_request WHERE status = 'pending'";
$membershipPendingQuery="SELECT COUNT(*) AS memb_count FROM membership_requests WHERE status = 'pending'";
$equipmentPendingQuery = "SELECT COUNT(*) AS eqp_count FROM pending_equipment WHERE status = 'pending'";
$RequipmentPendingQuery = "SELECT COUNT(*) AS rental_eqp_count FROM pending_rental WHERE status = 'pending'";
$RetEquipmentPendingQuery = "SELECT COUNT(*) AS return_eqp_count FROM pending_rental_return WHERE status = 'pending'";
$PaymentPendingQuery = "SELECT COUNT(*) AS return_payment_count FROM pending_payment WHERE status = 'pending'";


$productResult = mysqli_query($conn, $productPendingQuery);
$instructorResult = mysqli_query($conn, $instructorPendingQuery);
$membershipResult=mysqli_query($conn, $membershipPendingQuery);
$equipmentResult=mysqli_query($conn, $equipmentPendingQuery);
$RequipmentResult=mysqli_query($conn, $RequipmentPendingQuery);
$RetEquipmentResult=mysqli_query($conn, $RetEquipmentPendingQuery);
$PaymentResult=mysqli_query($conn, $PaymentPendingQuery);

$productCount = mysqli_fetch_assoc($productResult)['product_count'];
$instructorCount = mysqli_fetch_assoc($instructorResult)['instructor_count'];
$membershipCount =mysqli_fetch_assoc($membershipResult)['memb_count'];
$equipmentCount =mysqli_fetch_assoc($equipmentResult)['eqp_count'];
$RequipmentCount =mysqli_fetch_assoc($RequipmentResult)['rental_eqp_count'];
$RetEquipmentCount =mysqli_fetch_assoc($RetEquipmentResult)['return_eqp_count'];
$paymentCount =mysqli_fetch_assoc($PaymentResult)['return_payment_count'];

// Only show the message if both are zero
if ($productCount == 0 && $instructorCount == 0 && $membershipCount==0 && $equipmentCount==0  
&& $RequipmentCount==0 && $RetEquipmentCount==0 && $paymentCount==0) {
    echo "<p>No pending requests at the moment.</p>";
}
?>
        </div>
    </section>

    <!-- Manage Instructors Section -->
    <section class="admin-section" id="instructors">
        <h2>Instructors and Workouts</h2>
        <p>View and manage workouts and instructor assignments and schedules.</p>
        <a href="add_instructor.php" class="cta-button">Add Instructor</a>
        <a href="add_workout.php" class="cta-button">Add Workout</a>
        <a href="view_workouts_instructors.php" class="cta-button">View All</a>
    </section>

    <!-- Equipment and Products Section -->
    <section class="admin-section" id="equipment-products">
        <h2>Equipment & Products</h2>
        <p>Review, manage and add equipment and products.</p>
        <a href="add_eqp.php" class="cta-button">Add New Equipment</a>
        <a href="add_product.php" class="cta-button">Add New Product</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ShapeShifter Fitness and Gym. All rights reserved.</p>
    </footer>
</body>
</html>