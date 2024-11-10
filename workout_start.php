<?php
session_start();
require 'connection.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start Workout - ShapeShifter Fitness</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>
    <h1 style="color: #FFD700; text-align:center;"><?php echo $workout['name']; ?></h1>
    <p style="text-align:center;"><?php echo $workout['description']; ?></p>
    <img src="<?php echo $workout['image_url']; ?>" alt="Workout Image" style="width:500px; height:auto; margin-left:435px;">

    <form action="mark_done.php" method="POST">
        <input type="hidden" name="workout_id" value="<?php echo $workout_id; ?>">
        <button type="submit" style="width:100px;height:30px;margin-left:640px;">Mark as Done</button>
    </form>
</body>
</html>
