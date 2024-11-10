<?php
session_start();
require 'connection.php';

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id']; // Get the user ID from the session

    // Fetch all active workout plans for the user
    $sql_workouts = "SELECT w.workout_id, w.name AS workout_name, w.description AS workout_description, w.frequency, w.image_url
                     FROM workouts w 
                     JOIN users_workout uw ON w.workout_id = uw.workout_id
                     WHERE uw.user_id = $user_id AND uw.status = 'active'";

    $result_workouts = $conn->query($sql_workouts);

    if ($result_workouts->num_rows > 0) {
        $workouts = [];
        while ($row = $result_workouts->fetch_assoc()) {
            $workouts[] = $row; // Store each workout's data in an array
        }
    } else {
        $no_workouts_message = "No active workouts assigned.";
    }

} else {
    // Redirect to login page if session is not set
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workout Dashboard - ShapeShifter Fitness</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
            padding: 20px;
        }
        h1 { color: #FFD700; } /* Gold color */
        h2, h3 { color: #ADD8E6; } /* Light blue color */
        .workout-plan {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            color: white;
        }
        .workout-plan img {
            max-width: 150px;
            margin-right: 15px;
        }
        .workout-details {
            max-width: 600px;
        }
        .cta-button {
            padding: 10px 20px;
            background-color: green;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- User's Active Workout Plans -->
    <h1>Your Active Workout Plans</h1>
    <?php if (isset($workouts) && !empty($workouts)) { ?>
        <?php foreach ($workouts as $workout) { ?>
            <div class="workout-plan">
                <img src="<?php echo $workout['image_url']; ?>" alt="<?php echo $workout['workout_name']; ?> Image">
                <div class="workout-details">
                    <h3><?php echo $workout['workout_name']; ?></h3>
                    <p><?php echo $workout['workout_description']; ?></p>
                    <p><strong>Frequency:</strong> <?php echo $workout['frequency']; ?></p>
                    <button class="cta-button" onclick="window.location.href='workout_start.php?workout_id=<?php echo $workout['workout_id']; ?>'">Start this Workout</button>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p><?php echo $no_workouts_message; ?></p>
    <?php } ?>
</body>
</html>
