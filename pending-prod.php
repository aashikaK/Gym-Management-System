<section class="admin-section" id="requests">
        <h2>Pending Requests</h2>
        <div class="requests">
        <?php
            // Fetch pending requests
            $sql = "SELECT p.*, u.username,u.id, pd.name 
        FROM pending_product p
        INNER JOIN users_login u ON p.req_userid = u.id
        INNER JOIN products pd ON p.p_id = pd.id
        WHERE p.status = 'Pending'";

            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $productName = $row['name'];
                    $pid=$row['p_id'];
                    $requestedQty = $row['requested_qty'];
                    $username = $row['username'];
                    $user_id=$row['id'];
                    ?>
                   <form action="" method="post">
    <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
    <p><strong>Product:</strong> <?= htmlspecialchars($productName); ?></p>
    <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
    <button class="approve-button" name="approve_prod" type="submit">Approve</button>
    <button class="reject-button" name="reject_prod" type="submit">Reject</button> 
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($productName); ?>">
    <input type="hidden" name="requested_qty" value="<?= htmlspecialchars($requestedQty); ?>">
    <input type="hidden" name="p_id" value="<?= htmlspecialchars($pid); ?>">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">

</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_prod'])) {
                $productName = $_POST['product_name'];
                $requestedQty = $_POST['requested_qty'];
                $user_id = $_POST['user_id'];
                // Update the pending_product status
                $updatePending = "UPDATE pending_product SET status='complete', approved_date=NOW() WHERE p_id='$pid'";
                mysqli_query($conn, $updatePending);

                // Update the product quantity
                $updateProduct = "UPDATE products SET available_qty = available_qty - $requestedQty WHERE name='$productName'";
                mysqli_query($conn, $updateProduct);

                $paySql="UPDATE product_payment
                SET status = 'complete'
                WHERE user_id=$user_id AND p_id='$pid'";
                mysqli_query($conn,$paySql);

                $message = "Your request for buying $productName was approved. The product will be delivered soon.";
                $insertNotification = "INSERT INTO notifications (user_id, message,section) VALUES ('$user_id', '$message','prod_eqp')";
                mysqli_query($conn, $insertNotification);
            
                echo "Request approved and quantity updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_prod'])) {
                $pid = $_POST['p_id'];
                $productName = $_POST['product_name'];
                $user_id = $_POST['user_id'];
                // Update the status to 'rejected'
                $rejectQuery = "UPDATE pending_product SET status='rejected' WHERE p_id='$pid'";
                mysqli_query($conn, $rejectQuery);
            
                $message = "Your request for buying $productName was denied by the admin.";
                $insertNotification = "INSERT INTO notifications (user_id, message,section) VALUES ('$user_id', '$message','prod_eqp')";
                mysqli_query($conn, $insertNotification);
                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>