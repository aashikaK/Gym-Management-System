<?php
$reteqpsql = "SELECT rer.*, u.username, eq.name, r.available_rental_qty 
           FROM pending_rental_return rer
           INNER JOIN users_login u ON rer.user_id = u.id
           INNER JOIN rental_equipments r ON rer.e_id = r.equipment_id
           INNER JOIN equipment eq ON r.equipment_id = eq.equipment_id
           WHERE rer.status = 'pending'";

$retEqpresult = mysqli_query($conn, $reteqpsql);

if(mysqli_num_rows($retEqpresult) > 0) {
    while ($row = mysqli_fetch_assoc($retEqpresult)) {
        $equipmentName = $row['name'];
        $eid = $row['e_id'];
        $username = $row['username'];
        $rrid=$row['id'];
        $user_id=$row['user_id'];
        ?>
        <form action="" method="post">
            <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Returning Equipment:</strong> <?= htmlspecialchars($equipmentName); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_return_date']); ?></p>
            <button class="approve-button" name="approve_reteqp" type="submit">Approve</button>
            <button class="reject-button" name="reject_reteqp" type="submit">Reject</button> 
            <input type="hidden" name="retequipment_name" value="<?= htmlspecialchars($equipmentName); ?>">
            <input type="hidden" name="rete_id" value="<?= htmlspecialchars($eid); ?>">
            <input type="hidden" name="rr_id" value="<?= htmlspecialchars($rrid); ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">
            <input type="hidden" name="available_qty" value="<?= htmlspecialchars($row['available_rental_qty']); ?>">
        </form>
        <?php
    }
}

// Handle Approve Request
if (isset($_POST['approve_reteqp'])) {
    $EquipmentName = $_POST['retequipment_name'];
    $reteid = $_POST['rete_id'];
    $rid=$_POST['rr_id'];
    $userid=$_POST['user_id'];
    // Update pending rental return status
    $updatePendingRetEqp = "UPDATE pending_rental_return SET status='complete', approved_return_date=NOW() WHERE id= '$rid' ";
    mysqli_query($conn, $updatePendingRetEqp);

    
    $update_rental_transaction="UPDATE rental_transactions SET is_returned=1 where rental_id='$reteid' and user_id='$userid'";
    mysqli_query($conn,$update_rental_transaction);
    // Increase quantity
    $updateRetEquipment = "UPDATE rental_equipments SET available_rental_qty = available_rental_qty + 1 WHERE equipment_id='$reteid'";
    mysqli_query($conn, $updateRetEquipment);

    $message = "Your request for returning $EquipmentName was approved."; 
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','return_rental')";
    mysqli_query($conn, $insertNotification);

    echo "Request approved and quantity updated.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Reject Request
if (isset($_POST['reject_reteqp'])) {
    $reteid = $_POST['rete_id'];
    $EquipmentName = $_POST['retequipment_name'];

    // Update status to 'rejected'
    $rejectRetEqpQuery = "UPDATE pending_rental_return SET status='rejected' WHERE id= '$rid' ";
    mysqli_query($conn, $rejectRetEqpQuery);

    $message = "Your request for returning $EquipmentName was rejected."; 
    $insertNotification = "INSERT INTO notifications (user_id, message, section) VALUES ('$userid', '$message','return_rental')";
    mysqli_query($conn, $insertNotification);
    
    echo "The request was rejected.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
