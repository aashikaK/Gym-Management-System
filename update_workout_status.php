<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Check if form is submitted before accessing $_POST variables
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStatus'])) {
    if (isset($_POST['workout_id']) && isset($_POST['date_completed'])) {
        $workout_id = $_POST['workout_id'];
        $date_completed = $_POST['date_completed'];

        // Fetch workout details
        $workout_query = "SELECT * FROM workouts WHERE workout_id = $workout_id";
        $workout_result = $conn->query($workout_query);
        $workout = $workout_result->fetch_assoc();
        $required_frequency = (int) $workout['frequency'];

        // Fetch user's tracking progress
        $track_query = "SELECT * FROM user_workout_tracking WHERE user_id = $user_id AND workout_id = $workout_id";
        $track_result = $conn->query($track_query);
        $tracking = $track_result->fetch_assoc();

        $completed_dates = $tracking ? explode(',', $tracking['completed_dates']) : [];
        $times_completed = $tracking['times_completed'] ?? 0;

        if (!in_array($date_completed, $completed_dates)) {
            $completed_dates[] = $date_completed;
            $times_completed++;
        }

        if ($tracking) {
            $update_query = "UPDATE user_workout_tracking 
                            SET completed_dates = '" . implode(',', $completed_dates) . "', 
                                times_completed = $times_completed 
                            WHERE user_id = $user_id AND workout_id = $workout_id";
        } else {
            $update_query = "INSERT INTO user_workout_tracking (user_id, workout_id, completed_dates, times_completed) 
                            VALUES ($user_id, $workout_id, '" . implode(',', $completed_dates) . "', $times_completed)";
        }
        $conn->query($update_query);
    }
}

// Check if progress should be reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resetProgress'])) {
    if (isset($_POST['workout_id'])) {
        $workout_id = $_POST['workout_id'];
        $reset_query = "UPDATE user_workout_tracking SET completed_dates = '', times_completed = 0 WHERE user_id = $user_id AND workout_id = $workout_id";
        $conn->query($reset_query);
        $_SESSION['workout_reset'] = "You've completed your workout for this week! 🎉 Progress has been reset.";
        header("Location: workout_dashboard.php");
        exit();
    }
}

// Fetch workout details again for displaying
$workout_id = $_POST['workout_id'] ?? null;
$workout_query = "SELECT * FROM workouts WHERE workout_id = $workout_id";
$workout_result = $conn->query($workout_query);
$workout = $workout_result->fetch_assoc();
$required_frequency = (int) $workout['frequency'];

$track_query = "SELECT * FROM user_workout_tracking WHERE user_id = $user_id AND workout_id = $workout_id";
$track_result = $conn->query($track_query);
$tracking = $track_result->fetch_assoc();
$times_completed = $tracking['times_completed'] ?? 0;

$goal_completed = ($times_completed >= $required_frequency);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Workout Status</title>
    <style>
        body { background: black; color: white; font-family: Arial, sans-serif; text-align: center; }
        h1 { color: #FFD700; } 
        .form-group { margin-bottom: 10px; }
        .cta-button { padding: 10px 20px; background-color: green; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Update Status for <?php echo htmlspecialchars($workout['name']); ?></h1>
    <form method="post">
    <input type="hidden" name="workout_id" value="<?php echo htmlspecialchars($workout_id); ?>">
    
    <?php if (!$goal_completed): ?>
        <div class="form-group">
            <label>Enter the date you completed the workout:</label>
            <input type="date" name="date_completed" required>
        </div>
    <?php endif; ?>
    
    <?php if ($goal_completed): ?>
        <p style="color: lightgreen;">You've completed this workout for the week! 🎉</p>
        <button type="submit" name="resetProgress" class="cta-button">Start Again</button>
    <?php else: ?>
        <button type="submit" name="updateStatus" class="cta-button">Update Status</button>
    <?php endif; ?>
</form>

</body>
</html>
