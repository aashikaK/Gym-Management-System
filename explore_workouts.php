<?php
require_once "connection.php";
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}
$username = $_SESSION['username'];

$sql="SELECT * FROM workouts";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workouts</title>
</head>
<style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body Styling */
body {
    background-color: #121212;
    color: #f5f5f5;
    font-size: 16px;
    line-height: 1.6;
}

/* Workout Section Styling */
.workout-section {
    background: linear-gradient(135deg, #e0e0e0, #bdbdbd); /* Light gray gradient for workout section */
    padding: 40px 20px;
    border-radius: 8px;
    margin: 20px auto;
    width: 90%;
    max-width: 800px;
}

/* Workout Page Title */
h2 {
    color: #ffeb3b; /* Yellow color for section title */
    text-align: center;
    margin: 20px 0;
    font-size: 28px;
}

/* Workout Description */
p {
    color: #b3b3b3;
    text-align: center;
}

/* Container for Workouts */
.workout_container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

/* Workout Card Styling */
.workout_card {
    width: 80%;
    max-width: 400px;
    background-color: #1f1f1f;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    color: #f5f5f5;
}

/* Workout Image Styling */
.workout_image {
    width: 100%;
    border-radius: 8px;
    margin-bottom: 15px;
}

/* Workout Title */
.workout_card h3 {
    color: #4caf50; /* Green color for card titles */
    margin: 10px 0;
}

/* Button Styling */
.cta-button {
    background-color: #4caf50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.cta-button:hover {
    background-color: #66bb6a; /* Lighter green on hover */
}

/* Footer Section */
.footer {
    padding: 20px;
    background-color: #1f1f1f;
    text-align: center;
    color: #777;
}

.footer .social-media a {
    color: #f5f5f5;
    margin: 0 10px;
    font-size: 18px;
    transition: color 0.3s;
}

.footer .social-media a:hover {
    color: #4caf50; /* Green hover color for social icons */
}

</style>
<body>
    <h2>Workout Programs</h2>
    <p>Access your personalized workout plans and track progress.</p>
    <div class="workout_container">
        <?php
        // Loop through each workout and display it
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Fetch workout data
                $workout_id= htmlspecialchars($row['workout_id']);
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $frequency = htmlspecialchars($row['frequency']);
                $image = htmlspecialchars($row['image_url']);
                ?>
                <!-- Workout Card -->
                <div class="workout_card">
                    <img src="<?php echo $image; ?>" alt="<?php echo $name; ?> Image" class="workout_image">
                    <h3><?php echo $name; ?></h3>
                    <p><?php echo $description; ?></p>
                    <p><strong>Frequency:</strong> <?php echo $frequency; ?></p>
                    <form method="post" action="">
                        <input type="hidden" name="workout_id" value="<?php echo $workout_id; ?>">
                        <button type="submit" class="cta-button" name="add_workout">Add to my workout plan</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo "<p>No workouts available.</p>";
        }
        ?>
    </div>
    
    
</body>
</html>

<?php
$username=$_SESSION['username'];
$sql_ui = "SELECT ui.id FROM users_info ui
           JOIN users_login ul ON ul.id = ui.id 
           WHERE ul.username = '$username'";

$result = mysqli_query($conn, $sql_ui);

if ($result && mysqli_num_rows($result) > 0) {
    // User found, fetch user_id
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];
} else {
    // User not found
    echo "User not found.";
    exit;
}

if (isset($_POST['add_workout'])) {
    // Get workout_id from the form submission
    $workout_id = $_POST['workout_id'];
    
    // Insert the user_id and workout_id into the users_workout table
   $status= "active";
    $query = "INSERT INTO users_workout (user_id, workout_id, status) VALUES ($user_id, $workout_id, '$status')";
    
    $query_result = mysqli_query($conn, $query);
    
    if ($query_result) {
        echo "Added successfully!";
    } else {
        echo "Unable to add to user's workout.";
    }
} 