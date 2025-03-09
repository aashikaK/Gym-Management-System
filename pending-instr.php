<?php
            // Fetch pending requests
            $irsql = "SELECT distinct ir.*, u.username, i.name
        FROM instructor_request ir
        INNER JOIN users_login u ON ir.user_id = u.id
        INNER JOIN instructors i ON ir.instructor_id = i.id
        WHERE ir.status = 'pending'";

            $result_ir = mysqli_query($conn, $irsql);

            if(mysqli_num_rows($result_ir) > 0) {
                while ($row = mysqli_fetch_assoc($result_ir)) {
                    $instructorName = $row['name'];
                    $req_userid=$row['user_id'];
                    $i_id=$row['instructor_id'];
                    $username = $row['username'];
                    ?>
                   <form action="" method="post">
    <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
    <p><strong>Requested Instructor:</strong> <?= htmlspecialchars($instructorName); ?></p>
    <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
    <button class="approve-button" name="approve_instructor" type="submit">Approve</button>
    <button class="reject-button" name="reject_instructor" type="submit">Reject</button> 
    <input type="hidden" name="req_userid" value="<?= htmlspecialchars($req_userid); ?>">
    <input type="hidden" name="instr_id" value="<?= htmlspecialchars($i_id); ?>">
    <input type="hidden" name="instr_name" value="<?= htmlspecialchars($instructorName); ?>">
</form>

                    <?php
                }
            } 

            if (isset($_POST['approve_instructor'])) {
                $userid = $_POST['req_userid'];
                $instructor_id = $_POST['instr_id'];
                $instructorName = $_POST['instr_name']; // Correct instructor name
            
                // Update instructor request status
                $updateInstructor = "UPDATE instructor_request SET status='complete', approved_date=NOW() WHERE user_id='$userid'";
                mysqli_query($conn, $updateInstructor);
            
                // Assign instructor to user
                $updateUserInstructor = "UPDATE users_info SET instructor_id='$instructor_id' WHERE id='$userid'";
                mysqli_query($conn, $updateUserInstructor);
            
                // Insert notification with correct instructor name
                $message = "Your request for instructor $instructorName was approved.";
                $insertNotification = "INSERT INTO notifications (user_id, message,section) VALUES ('$userid', '$message','instructor')";
                mysqli_query($conn, $insertNotification);
            
                echo "Request approved.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            
            
            if (isset($_POST['reject_instructor'])) {
                $userid = $_POST['req_userid'];
                $instructor_id = $_POST['instr_id'];
                $instructorName = $_POST['instr_name']; // Correct instructor name
            
                // Update request status
                $rejectInstrQuery = "UPDATE instructor_request SET status='rejected' WHERE user_id='$userid'";
                mysqli_query($conn, $rejectInstrQuery);
            
                // Insert rejection notification with correct instructor name
                $message = "Your request for instructor $instructorName was denied.";
                $insertNotification = "INSERT INTO notifications (user_id, message,section) VALUES ('$userid', '$message','instructor')";
                mysqli_query($conn, $insertNotification);
            
                echo "Request rejected.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            
            ?>