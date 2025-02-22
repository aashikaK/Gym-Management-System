<?php
// Start the session
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ShapeShifter Fitness and Gym</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="left">
            <h1 class="title">
                <img class="logo" src="images/logo.jpg" alt="ShapeShifter Fitness and Gym logo">
                Admin Panel
            </h1>
        </div>
        <nav class="nav-bar">
            <a href="#requests">Pending Requests</a>
            <a href="#instructors">Instructors and Workouts</a>
            <a href="#equipment-products">Equipment & Products</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </header>

    <!-- Pending Requests Section -->
    <section class="admin-section" id="requests">
        <h2>Pending Requests</h2>
        <div class="requests">
        <?php
            // Fetch pending requests
            $sql = "SELECT p.*, u.username, pd.name 
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
</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_prod'])) {
                $productName = $_POST['product_name'];
                $requestedQty = $_POST['requested_qty'];
                // Update the pending_product status
                $updatePending = "UPDATE pending_product SET status='complete', approved_date=NOW() WHERE p_id='$pid'";
                mysqli_query($conn, $updatePending);

                // Update the product quantity
                $updateProduct = "UPDATE products SET available_qty = available_qty - $requestedQty WHERE name='$productName'";
                mysqli_query($conn, $updateProduct);
                $_SESSION['prod_req_approved']="Your request for buying $productName was approved. The product will be delivered soon";

                echo "Request approved and quantity updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_prod'])) {
                $pid = $_POST['p_id'];
                $productName = $_POST['product_name'];
                // Update the status to 'rejected'
                $rejectQuery = "UPDATE pending_product SET status='rejected' WHERE p_id='$pid'";
                mysqli_query($conn, $rejectQuery);
            
                $_SESSION['prod_req_denied'] = "Your request for buying $productName was denied by the admin.";
                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>

        <!-- For instructor -->
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
</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_instructor'])) {
                $userid = $_POST['req_userid'];
                $instructor_id= $_POST['instr_id'];
                // Update the pending_product status
                $updateInstructor = "UPDATE instructor_request SET status='complete', approved_date=NOW() WHERE user_id='$userid'";
                mysqli_query($conn, $updateInstructor);

                // Update the product quantity
                $updateUserInstructor = "UPDATE users_info SET instructor_id ='$instructor_id'  WHERE id='$userid'";
                mysqli_query($conn, $updateUserInstructor);
                $_SESSION['instructor_req_approved']="Your request for the instructor was approved.";

                echo "Request approved for the instructor and updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_instructor'])) {
                $instructor_id= $_POST['instr_id'];
                // Update the status to 'rejected'
                $rejectInstrQuery = "UPDATE instructor_request SET status='rejected' WHERE instructor_id=' $instructor_id'";
                mysqli_query($conn, $rejectInstrQuery);
            
                $_SESSION['instructor_req_denied'] = "Your request for the instructor was denied by the admin.";
                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>
  
  <!-- for membership -->

  <?php
// Fetch pending requests
$memreqsql = "SELECT DISTINCT m.*, u.username
FROM membership_requests m
INNER JOIN users_login u ON m.req_userid = u.id
WHERE m.status = 'pending'";

$result_membreq = mysqli_query($conn, $memreqsql);

if(mysqli_num_rows($result_membreq) > 0) {
    while ($row = mysqli_fetch_assoc($result_membreq)) {
        $req_type = $row['request_type'];
        $req_membType = $row['requested_membership_type'];
        $req_userid = $row['req_userid'];
        $username = $row['username'];
        ?>

        <form action="" method="post">
            <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Request type(membership):</strong> <?= htmlspecialchars($req_type); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>

            <?php if ($req_type == 'upgrade'): ?>
                <button class="approve-button" name="approve_upgrade" type="submit">Approve Upgrade</button>
                <button class="reject-button" name="reject_upgrade" type="submit">Reject Upgrade</button>
            <?php elseif ($req_type == 'renewal'): ?>
                <button class="approve-button" name="approve_renewal" type="submit">Approve Renewal</button>
                <button class="reject-button" name="reject_renewal" type="submit">Reject Renewal</button>
            <?php endif; ?>

            <input type="hidden" name="req_userid" value="<?= htmlspecialchars($req_userid); ?>">
            <input type="hidden" name="membreq_type" value="<?= htmlspecialchars($req_type); ?>">
            <input type="hidden" name="memb_type" value="<?= htmlspecialchars($req_membType); ?>">
        </form>

        <?php
    }
}

// Handle Renewal Approval
if (isset($_POST['approve_renewal'])) {
    $userid = $_POST['req_userid'];
    $updateMembInfo = "UPDATE users_info SET membership_expiry_date = DATE_ADD(NOW(), INTERVAL 1 YEAR) WHERE id = '$userid'";
    mysqli_query($conn, $updateMembInfo);

    $updateMembreq = "UPDATE membership_requests SET status='complete' WHERE req_userid='$userid' AND request_type='renewal'";
    mysqli_query($conn, $updateMembreq);

    $_SESSION['memb_req_approved'] = "Membership renewal approved.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Upgrade Approval
if (isset($_POST['approve_upgrade'])) {
    $userid = $_POST['req_userid'];
    $newType = $_POST['memb_type'];

    $updateMembInfo = "UPDATE users_info SET membership='$newType' WHERE id='$userid'";
    mysqli_query($conn, $updateMembInfo);

    $updateMembreq = "UPDATE membership_requests SET status='complete' WHERE req_userid='$userid' AND request_type='upgrade'";
    mysqli_query($conn, $updateMembreq);

    $_SESSION['memb_req_approved'] = "Membership upgrade approved.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Upgrade Rejection
if (isset($_POST['reject_upgrade'])) {
    $userid = $_POST['req_userid'];
    $updateRequest = "UPDATE membership_requests SET status='rejected' WHERE req_userid='$userid' AND request_type='upgrade'";
    $_SESSION['memb_req_denied'] = "Membership upgrade denied.";
   
    mysqli_query($conn, $updateRequest);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Renewal Rejection
if (isset($_POST['reject_renewal'])) {
    $userid = $_POST['req_userid'];
    $updateRequest = "UPDATE membership_requests SET status='rejected' WHERE req_userid='$userid' AND request_type='renewal'";
    mysqli_query($conn, $updateRequest);
    $_SESSION['memb_req_denied'] = "Membership renewal denied.";
   
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!-- EQUIPMENT BUY REQUEST APPROVAL OR REJCTION -->
<?php
            // Fetch pending requests
            $eqpsql = "SELECT e.*, u.username, eq.name 
        FROM pending_equipment e
        INNER JOIN users_login u ON e.req_userid = u.id
        INNER JOIN equipment eq ON e.e_id = eq.equipment_id
        WHERE e.status = 'pending'";

            $eresult = mysqli_query($conn, $eqpsql);

            if(mysqli_num_rows($eresult) > 0) {
                while ($row = mysqli_fetch_assoc($eresult)) {
                    $equipmentName = $row['name'];
                    $eid=$row['e_id'];
                    $requestedQty = $row['requested_qty'];
                    $username = $row['username'];
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
</form>
                    <?php
                }
            } 

            if (isset($_POST['approve_eqp'])) {
                $EquipmentName = $_POST['equipment_name'];
                $requestedQty = $_POST['requested_qty'];
                $eid=$_POST['e_id'];
                // Update the pending_product status
                $updatePendingEqp = "UPDATE pending_equipment SET status='complete', approved_date=NOW() WHERE e_id='$eid'";
                mysqli_query($conn, $updatePendingEqp);

                // Update the product quantity
                $updateEquipment = "UPDATE equipment SET available_quantity = available_quantity - $requestedQty WHERE name='$EquipmentName'";
                mysqli_query($conn, $updateEquipment);
                $_SESSION['eqp_req_approved']="Your request for buying $EquipmentName was approved. The product will be delivered soon";

                echo "Request approved and quantity updated.";
                header("Location: ".$_SERVER['PHP_SELF']); 
                exit();
            } 
            if (isset($_POST['reject_eqp'])) {
                $eid = $_POST['e_id'];
                $EquipmentName = $_POST['equipment_name'];
                
                // Update the status to 'rejected'
                $rejectEqpQuery = "UPDATE pending_equipment SET status='rejected' WHERE e_id='$eid'";
                mysqli_query($conn, $rejectEqpQuery);
            
                $_SESSION['eqp_req_denied'] = "Your request for buying $EquipmentName was denied by the admin.";
                echo "The request was rejected.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
            ?>

<!-- for rental equipment -->
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
        ?>
        <form action="" method="post">
            <p><strong>Requested user:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Returning Equipment:</strong> <?= htmlspecialchars($equipmentName); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_return_date']); ?></p>
            <button class="approve-button" name="approve_reteqp" type="submit">Approve</button>
            <button class="reject-button" name="reject_reteqp" type="submit">Reject</button> 
            <input type="hidden" name="retequipment_name" value="<?= htmlspecialchars($equipmentName); ?>">
            <input type="hidden" name="rete_id" value="<?= htmlspecialchars($eid); ?>">
            <input type="hidden" name="available_qty" value="<?= htmlspecialchars($row['available_rental_qty']); ?>">
        </form>
        <?php
    }
}

// Handle Approve Request
if (isset($_POST['approve_reteqp'])) {
    $EquipmentName = $_POST['retequipment_name'];
    $reteid = $_POST['rete_id'];

    // Update pending rental return status
    $updatePendingRetEqp = "UPDATE pending_rental_return SET status='complete', approved_return_date=NOW() WHERE e_id= '$reteid' ";
    mysqli_query($conn, $updatePendingRetEqp);

    // Increase quantity
    $updateRetEquipment = "UPDATE rental_equipments SET available_rental_qty = available_rental_qty + 1 WHERE equipment_id='$reteid'";
    mysqli_query($conn, $updateRetEquipment);

    $_SESSION['retE_req_approved'] = "Your request for returning $EquipmentName was approved. The product will be delivered soon";

    echo "Request approved and quantity updated.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Reject Request
if (isset($_POST['reject_reteqp'])) {
    $reteid = $_POST['rete_id'];
    $EquipmentName = $_POST['retequipment_name'];

    // Update status to 'rejected'
    $rejectRetEqpQuery = "UPDATE pending_rental_return SET status='rejected' WHERE e_id= '$reteid' ";
    mysqli_query($conn, $rejectRetEqpQuery);

    $_SESSION['retE_req_denied'] = "Your request for returning $EquipmentName was denied by the admin.";
    
    echo "The request was rejected.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- Payment approval or rejection -->
<?php
$paymentsql = "SELECT p.*, u.username
           FROM pending_payment p
           INNER JOIN users_login u ON p.req_userid = u.id
           WHERE p.status = 'pending'";

$presult = mysqli_query($conn, $paymentsql);

if(mysqli_num_rows($presult) > 0) {
    while ($row = mysqli_fetch_assoc($presult)) {
        $userid = $row['req_userid'];
        $username = $row['username'];
        $amount_paid = $row['amount_paid'];
        $request_id = $row['id'];
        $reqDate= $row['requested_date'];
        ?>
        <form action="" method="post">
        <p><strong>Requested User:</strong> <?= htmlspecialchars($username); ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?= number_format($amount_paid, 2); ?></p>
            <p><strong>Requested Date:</strong> <?= htmlspecialchars($row['requested_date']); ?></p>
            
            <button class="approve-button" name="approve_payment" type="submit">Approve</button>
            <button class="reject-button" name="reject_payment" type="submit">Reject</button> 
            
            <input type="hidden" name="payment_id" value="<?= htmlspecialchars($request_id); ?>">
            <input type="hidden" name="userid" value="<?= htmlspecialchars($userid); ?>">
            <input type="hidden" name="amount_paid" value="<?= htmlspecialchars($amount_paid); ?>">
        </form>
        <?php
    }
}

// Handle Approve Request
if (isset($_POST['approve_payment'])) {
    $payment_id = $_POST['payment_id'];
    $userid = $_POST['userid'];
    $amount_paid = $_POST['amount_paid'];


    // Approve the payment
    $updatePendPayment = "UPDATE pending_payment SET status = 'Approved', approved_date = NOW() WHERE id = '$payment_id'";
    mysqli_query($conn, $updatePendPayment);

    // Update users_info: Reset due & extend due date by 1 month from requested date
    $updatePayment = "UPDATE users_info 
                       SET payment_due = 0, 
                       status='paid',
                           payment_due_date = DATE_ADD('$reqDate', INTERVAL 1 MONTH) 
                       WHERE id = '$userid'";
    mysqli_query($conn, $updatePayment);

    $_SESSION['payment_success'] = "Payment approved! Next due date: " . date('Y-m-d', strtotime($requested_date . ' +1 month'));

    echo "Payment approved. Due date updated.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Reject Request
if (isset($_POST['reject_payment'])) {
    $payment_id = $_POST['payment_id'];

    // Update status to 'Rejected'
    $rejectPaymentQuery = "UPDATE pending_payment SET status = 'Rejected' WHERE id= '$payment_id'";
    mysqli_query($conn, $rejectPaymentQuery);

    $_SESSION['payment_error'] = "Payment request was rejected.";
    
    echo "The request was rejected.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?> 

<?php
// Check if there are any pending requests in both tables
$productPendingQuery = "SELECT COUNT(*) AS product_count FROM pending_product WHERE status = 'pending'";
$instructorPendingQuery = "SELECT COUNT(*) AS instructor_count FROM instructor_request WHERE status = 'pending'";
$membershipPendingQuery="SELECT COUNT(*) AS memb_count FROM membership_requests WHERE status = 'pending'";
$equipmentPendingQuery = "SELECT COUNT(*) AS eqp_count FROM pending_equipment WHERE status = 'pending'";
$RequipmentPendingQuery = "SELECT COUNT(*) AS rental_eqp_count FROM pending_rental WHERE status = 'pending'";
$RetEquipmentPendingQuery = "SELECT COUNT(*) AS return_eqp_count FROM pending_rental_return WHERE status = 'pending'";
$PaymentPendingQuery = "SELECT COUNT(*) AS return_payment_count FROM pending_payment WHERE status = 'pending'";


$productResult = mysqli_query($conn, $productPendingQuery);
$instructorResult = mysqli_query($conn, $instructorPendingQuery);
$membershipResult=mysqli_query($conn, $membershipPendingQuery);
$equipmentResult=mysqli_query($conn, $equipmentPendingQuery);
$RequipmentResult=mysqli_query($conn, $RequipmentPendingQuery);
$RetEquipmentResult=mysqli_query($conn, $RetEquipmentPendingQuery);
$PaymentResult=mysqli_query($conn, $PaymentPendingQuery);

$productCount = mysqli_fetch_assoc($productResult)['product_count'];
$instructorCount = mysqli_fetch_assoc($instructorResult)['instructor_count'];
$membershipCount =mysqli_fetch_assoc($membershipResult)['memb_count'];
$equipmentCount =mysqli_fetch_assoc($equipmentResult)['eqp_count'];
$RequipmentCount =mysqli_fetch_assoc($RequipmentResult)['rental_eqp_count'];
$RetEquipmentCount =mysqli_fetch_assoc($RetEquipmentResult)['return_eqp_count'];
$paymentCount =mysqli_fetch_assoc($PaymentResult)['return_payment_count'];

// Only show the message if both are zero
if ($productCount == 0 && $instructorCount == 0 && $membershipCount==0 && $equipmentCount==0  
&& $RequipmentCount==0 && $RetEquipmentCount==0 && $paymentCount==0) {
    echo "<p>No pending requests at the moment.</p>";
}
?>
        </div>
    </section>

    <!-- Manage Instructors Section -->
    <section class="admin-section" id="instructors">
        <h2>Instructors and Workouts</h2>
        <p>View and manage workouts and instructor assignments and schedules.</p>
        <a href="add_instructor.php" class="cta-button">Add Instructor</a>
        <a href="add_workout.php" class="cta-button">Add Workout</a>
        <a href="view_workouts_instructors.php" class="cta-button">View All</a>
    </section>

    <!-- Equipment and Products Section -->
    <section class="admin-section" id="equipment-products">
        <h2>Equipment & Products</h2>
        <p>Review, manage and add equipment and products.</p>
        <a href="add_eqp.php" class="cta-button">Add New Equipment</a>
        <a href="add_product.php" class="cta-button">Add New Product</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ShapeShifter Fitness and Gym. All rights reserved.</p>
    </footer>
</body>
</html>
