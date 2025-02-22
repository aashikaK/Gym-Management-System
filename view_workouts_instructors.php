<?php
require 'connection.php';

$success_message = '';// Get the user ID from the session

    // Fetch all active workout plans for the user
   // Fetch all active workouts for all users
   $sql_workouts = "SELECT workout_id, name AS workout_name, description AS workout_description, frequency, image_url
   FROM workouts";
   
$result_workouts = $conn->query($sql_workouts);

if ($result_workouts->num_rows > 0) {
$workouts = [];
while ($row = $result_workouts->fetch_assoc()) {
$workouts[] = $row;
}
} else {
$no_workouts_message = "No active workouts available.";
}


    // Fetch all available instructors
    $sql_instructors = "SELECT id, name, specialty, experience, image_url FROM instructors WHERE availability = 1 AND status = 'active'";
    $result_instructors = $conn->query($sql_instructors);

    if ($result_instructors->num_rows > 0) {
        $instructors = [];
        while ($row = $result_instructors->fetch_assoc()) {
            $instructors[] = $row;
        }
    } else {
        $no_instructors_message = "No instructors available currently.";
    }
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <title>Workout Dashboard - ShapeShifter Fitness</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">



    <style>
        /* General Body Styling */
        body {
            background: linear-gradient(135deg, #000000, #1f1f1f);
            color: #f5f5f5;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1{
            color:#4caf50;
        }

        /* Section Wrapper */
        .section-wrapper {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        /* Workout and Instructor Card Styling */
        .workout-plan, .instructor-card {
            background: linear-gradient(135deg, #333, #444);
            padding: 20px;
            border-radius: 12px;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            text-align: center;
            max-width: 800px; /* Increased max-width for larger cards */
            transition: transform 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .workout-plan:hover, .instructor-card:hover {
            transform: scale(1.05);
        }

        /* Gradient Variations */
        .workout-plan:nth-child(1), .instructor-card:nth-child(1) {
            background: linear-gradient(135deg, #4a148c, #7b1fa2);
        }
        .workout-plan:nth-child(2), .instructor-card:nth-child(2) {
            background: linear-gradient(135deg, #283593, #5c6bc0);
        }
        .workout-plan:nth-child(3), .instructor-card:nth-child(3) {
            background: linear-gradient(135deg, #00695c, #26a69a);
        }

        /* Image Styling */
        .workout-plan img, .instructor-card img {
            width: 100%; /* Full width of the card */
            max-width: 500px; /* Increased max width for better visibility */
            border-radius: 8px;
            margin: 15px auto;
            display: block;
        }

        /* Title and Text Styling */
        .workout-plan h3, .instructor-card h3 {
            color: gold; /* Golden color for headings */
            margin: 10px 0;
            font-size: 24px; /* Slightly larger font for visibility */
        }

        .workout-plan p, .instructor-card p {
            color: white;
            font-size: 18px; /* Increased font size for better readability */
            margin: 10px 0;
            text-align: left; /* Align text for readability */
            max-width: 700px; /* Ensure text doesn't stretch too wide */
        }

        /* Success Message Styling */
        .success-message {
            color: #4caf50; /* Green color matching button */
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Button Styling */
        .cta-button {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 25px; /* Slightly larger button */
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }

        .cta-button:hover {
            background-color: #66bb6a;
        }

        /* Footer Styling */
        .footer {
            padding: 20px;
            background-color: #121212;
            text-align: center;
            color: #777;
            color: #f5f5f5;
            margin: 0 10px;
            font-size: 18px;
            transition: color 0.3s;
        }


        
    </style>
</head>
<body>
<h1>Available Workouts</h1>
<?php if ($success_message) { ?>
    <div class="success-message"><?php echo $success_message; ?></div>
<?php } ?>
<div class="section-wrapper">
    <?php if (isset($workouts) && !empty($workouts)) { ?>
        <?php foreach ($workouts as $index => $workout) { ?>
            <div class="workout-plan">
                <img src="<?php echo htmlspecialchars($workout['image_url']); ?>" alt="<?php echo htmlspecialchars($workout['workout_name']); ?>">
                <h3><?php echo htmlspecialchars($workout['workout_name']); ?></h3>
                <p><?php echo htmlspecialchars($workout['workout_description']); ?></p>
                <p><strong>Frequency:</strong> <?php echo htmlspecialchars($workout['frequency']); ?></p>
                <form method="post">
                    <input type="hidden" name="workout_id" value="<?php echo $workout['workout_id']; ?>">
                    <button type="submit" name="del_workout" class="cta-button">Delete this workout</button>
                </form>
            </div>
        <?php } ?>
    <?php } else { echo $no_workouts_message ?? "No active workouts assigned."; } ?>
    
    <h1>Available Instructors</h1>
    <?php if (isset($instructors) && !empty($instructors)) { ?>
        <?php foreach ($instructors as $instructor) { ?>
            <div class="instructor-card">
                <img src="<?php echo htmlspecialchars($instructor['image_url']); ?>" alt="<?php echo htmlspecialchars($instructor['name']); ?>">
                <h3><?php echo htmlspecialchars($instructor['name']); ?></h3>
                <p><strong>Specialty:</strong> <?php echo htmlspecialchars($instructor['specialty']); ?></p>
                <p><strong>Experience:</strong> <?php echo htmlspecialchars($instructor['experience']); ?> years</p>
                <form method="post">
                    <input type="hidden" name="instructor_id" value="<?php echo $instructor['id']; ?>">
                    <button type="submit" class="cta-button" name="activeBtn">Make Active</button>
                    <button type="submit" class="cta-button" name="inactiveBtn">Make Inactive</button>
                </form>
            </div>
        <?php } ?>
    <?php } else { echo $no_instructors_message ?? "No instructors available."; } ?>
</div>

<div class="footer">
    <p>&copy; 2024 ShapeShifter Fitness. All Rights Reserved.</p>
        
</div>
</body>
</html>

<?php
if(isset($_POST['activeBtn'])) {
    $instrId=$_POST['instructor_id'];
    $sql="UPDATE instructors SET status='active' where id='$instrId' ";
    mysqli_query($conn,$sql);
    }
    if(isset($_POST['inactiveBtn'])) {
        $instrId=$_POST['instructor_id'];
        $sql="UPDATE instructors SET status='inactive' where id='$instrId' ";
        mysqli_query($conn,$sql);
        }
        if(isset($_POST['del_workout'])) {
            $w_id=$workout['workout_id'];
            $sql="DELETE FROM workouts  WHERE workout_id='$w_id' ";
            mysqli_query($conn,$sql);
            }

?>
