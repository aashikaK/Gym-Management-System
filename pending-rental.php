<?php
            // Fetch pending requests
            $reqpsql = "SELECT re.*, u.username, eqp.name 
            FROM pending_rental re
            INNER JOIN users_login u ON re.user_id = u.id
            INNER JOIN rental_equipments req ON re.e_id = req.equipment_id
            INNER JOIN equipment eqp ON req.equipment_id = eqp.equipment_id
            WHERE re.status = 'pending'";
        

            $reresult = mysqli_query($conn, $reqpsql);

            if(mysqli_num_rows($reresult) > 0) {
                while ($row = mysqli_fetch_assoc($reresult)) {
                    $equipmentName = $row['name'];
                    $eid=$row['e_id'];
                    $requestedQty = $row['requested_qty'];
                    $username = $row['username'];
                    $rid=$row['r_id'];
                    $req_userid=$row['user_id'];
                    ?>
                   <form action="" method="post">
    <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
    <p><strong>Rental Equipment:</strong> <?= htmlspecialchars($equipmentName); ?></p>
    <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
    <button class="approve-button" name="approve_reqp" type="submit">Approve</button>
    <button class="reject-button" name="reject_reqp" type="submit">Reject</button> 
    <input type="hidden" name="requipment_name" value="<?= htmlspecialchars($equipmentName); ?>">
    <input type="hidden" name="requested_qty" value="<?= htmlspecialchars($requestedQty); ?>">
    <input type="hidden" name="re_id" value="<?= htmlspecialchars($eid); ?>">  
    <input type="hidden" name="r_id" value="<?= htmlspecialchars($rid); ?>"> 
    <input type="hidden" name="req_uid" value="<?= htmlspecialchars($req_userid); ?>">
</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_reqp'])) {
                $EquipmentName = $_POST['requipment_name'];
                $requestedQty = $_POST['requested_qty'];
                $eid=$_POST['re_id'];
                $rid=$_POST['r_id'];
                $userid=$_POST['req_uid'];
                // Update the pending_product status
                $updatePendingREqp = "UPDATE pending_rental SET status='complete', approved_date=NOW() WHERE r_id='$rid'";
                mysqli_query($conn, $updatePendingREqp);

                
                // Update the product quantity
                $updateREquipment = "UPDATE rental_equipments re
                     JOIN pending_rental pr ON re.equipment_id = pr.e_id
                     SET re.available_rental_qty = re.available_rental_qty - $requestedQty
                     WHERE pr.r_id = $rid";
                    mysqli_query($conn, $updateREquipment);
                // $_SESSION['eqp_req_approved']="Your request for buying $EquipmentName was approved. The product will be delivered soon";

                $paySql="UPDATE rental_payment
                SET status = 'complete'
                WHERE user_id=$userid AND e_id='$eid'";
                mysqli_query($conn,$paySql);

                $message = "Your request for renting $EquipmentName was approved. The equipment will be delivered soon."; 
                $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','rental')";
               
                mysqli_query($conn, $insertNotification);

                echo "Request approved and quantity updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_reqp'])) {
                $eid = $_POST['e_id'];
                $EquipmentName = $_POST['equipment_name'];
                $rid=$_POST['r_id'];
                
                $req_userid=$_POST['req_uid'];
                
                // Update the status to 'rejected'
                $rejectEqpQuery = "UPDATE pending_rental SET status='rejected' WHERE r_id='$rid'";
                mysqli_query($conn, $rejectEqpQuery);
            
                
                $message = "Your request for renting $EquipmentName was denied."; 
                $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$req_userid', '$message','rental')";
                mysqli_query($conn, $insertNotification);

                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>