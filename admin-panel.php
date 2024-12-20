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

    <!-- Pending Requests Section -->
    <section class="admin-section" id="requests">
        <h2>Pending Requests</h2>
        <div class="requests">
            <?php
            // Fetch pending requests
            $sql = "SELECT * FROM pending_product WHERE status = 'Pending'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="request-item">
                        <p><strong>Product:</strong> <?= $row['name'] ?></p>
                        <p><strong>Requested Date:</strong> <?= $row['requested_date'] ?></p>
                        <button class="approve-button" onclick="handleApproval(<?= $row['id'] ?>)">Approve</button>
                        <button class="reject-button" onclick="handleRejection(<?= $row['id'] ?>)">Reject</button>
                    </div>
                    <?php
                }
            } else {
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
        <a href="explore_workouts_instructors.php" class="cta-button">View All</a>
    </section>

    <!-- Equipment and Products Section -->
    <section class="admin-section" id="equipment-products">
        <h2>Equipment & Products</h2>
        <p>Review and manage equipment and product requests.</p>
        <a href="view_equipment_requests.php" class="cta-button">View Requests</a>
        <a href="add_product.php" class="cta-button">Add New Product</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ShapeShifter Fitness and Gym. All rights reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function handleApproval(id) {
            if (confirm("Are you sure you want to approve this request?")) {
                sendRequest(id, "approve");
            }
        }

        function handleRejection(id) {
            if (confirm("Are you sure you want to reject this request?")) {
                sendRequest(id, "reject");
            }
        }

        function sendRequest(id, action) {
            fetch("handle_request.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&action=${action}`,
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Refresh to update the list
            })
            .catch(error => console.error("Error:", error));
        }
    </script>
</body>
</html>
