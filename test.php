<?php

require 'connection.php';
// Fetch pending requests
            $eqpsql = "SELECT e.*, u.username, eq.name 
        FROM pending_equipment e
        INNER JOIN users_login u ON e.req_userid = u.id
        INNER JOIN equipment eq ON e.e_id = eq.equipment_id
        WHERE e.status = 'pending'";
$eresult = mysqli_query($conn, $eqpsql);
if (!$eresult) {
    die("SQL Error: " . mysqli_error($conn));
}
            //$eresult = mysqli_query($conn, $eqpsql);

            if(mysqli_num_rows($eresult) > 0) {
                while ($row = mysqli_fetch_assoc($eresult)) {
                    $equipmentName = $row['name'];
                    $eid=$row['e_id'];
                    $requestedQty = $row['requested_qty'];
                    $username = $row['username'];
                    $req_id=$row['id'];
                    $req_userid=$row['req_userid'];
                    ?>
                   <form action="" method="post">
    <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
    <p><strong>Equipment:</strong> <?= htmlspecialchars($equipmentName); ?></p>
    <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
    <button class="approve-button" name="approve_eqp" type="submit">Approve</button>
    <button class="reject-button" name="reject_eqp" type="submit">Reject</button> 
    <input type="hidden" name="equipment_name" value="<?= htmlspecialchars($equipmentName); ?>">
    <input type="hidden" name="requested_qty" value="<?= htmlspecialchars($requestedQty); ?>">
    <input type="hidden" name="e_id" value="<?= htmlspecialchars($eid); ?>">
    <input type="hidden" name="req_id" value="<?= htmlspecialchars($req_id); ?>">
    <input type="hidden" name="req_uid" value="<?= isset($req_userid) ? htmlspecialchars($req_userid) : ''; ?>">

</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_eqp'])) {

                if (isset($_POST['req_uid']) && !empty($_POST['req_uid'])) {
                    $userid = $_POST['req_uid'];
                } else {
                    die("Error: Request user ID is missing.");
                }
                
                $EquipmentName = $_POST['equipment_name'];
                $requestedQty = $_POST['requested_qty'];
                $eid=$_POST['e_id'];
                $reqid=$_POST['req_id'];
                $userid=$_POST['req_uid'];
                // Update the pending_product status
                $updatePendingEqp = "UPDATE pending_equipment SET status='complete', approved_date=NOW() WHERE id='$eid'";
                mysqli_query($conn, $updatePendingEqp);

                // Update the product quantity
                $updateEquipment = "UPDATE equipment SET available_quantity = available_quantity - $requestedQty WHERE name='$EquipmentName'";
                mysqli_query($conn, $updateEquipment);

                $message = "Your request for buying $EquipmentName has been approved by admin. The equipment will be delivered soon"; 
                $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','prod_equip')";
                mysqli_query($conn, $insertNotification);

                echo "Request approved and quantity updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_eqp'])) {
                $eid = $_POST['e_id'];
                $EquipmentName = $_POST['equipment_name'];
                $userid=$_POST['req_uid'];
                // Update the status to 'rejected'
                $rejectEqpQuery = "UPDATE pending_equipment SET status='rejected' WHERE e_id='$eid'";
                mysqli_query($conn, $rejectEqpQuery);
                
                $message = "Your request for buying $EquipmentName was denied by the admin"; 
                $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','prod_equip')";
                mysqli_query($conn, $insertNotification);

                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>