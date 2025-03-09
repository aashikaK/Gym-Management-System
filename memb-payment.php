<?php
session_start();
require "connection.php";

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Membership renewal details
$product_id_renewal = 1;
$product_name_renewal = "Membership Renewal";
$product_price_renewal = 1000;




// Check if the user has already requested renewal or upgrade
$userid = $_SESSION['id'];
$check_renewal_sql = "SELECT req_userid, request_type FROM membership_requests WHERE req_userid = ? AND request_type = 'renewal'";

// Prepare and execute renewal check
$stmt = $conn->prepare($check_renewal_sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->store_result();
$renewal_exists = $stmt->num_rows > 0;
$stmt->close();


$statussql="SELECT status FROM membership_requests WHERE req_userid=$userid";
        $result = mysqli_query($conn,$statussql);
         $status= mysqli_fetch_assoc($result);

// Redirect if renewal already exists
if ($renewal_exists&& $status['status']=='pending') {
    $_SESSION['renewal_req_exists'] = "You have already submitted a membership renewal request.";
    header('Location: dashboard.php');
    exit;
}



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_type = $_POST['request_type'];
    $location = $_POST['location'];
    $bank_account = $_POST['bank_account'];
    $request_date = date('Y-m-d H:i:s');
    $membership_type = $_POST['membership_type'] ?? null;

    if ($request_type == 'renewal') {
        $sql = "INSERT INTO membership_requests (req_userid, request_type, requested_date, status) 
                VALUES (?, 'renewal',  ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userid, $request_date);


       
        if ($stmt->execute() ) {
            $_SESSION['mem_payment_success'] = "Your membership renewal request has been successfully submitted!";

            
            $pay_sql="INSERT INTO memb_payment(user_id,amount,status) VALUES($userid,1000,'pending')";
            mysqli_query($conn,$pay_sql);
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

        
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Membership</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 50px;
        }

        form {
            margin-left: 500px;
            margin-top: 90px;
            width: 400px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .total-price {
            color: #28a745;
            font-weight: bold;
            margin-top: 5px;
        }

        .free-delivery {
            color: blue;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
        }

        .buy-button {
            text-align: center;
            margin-top: 20px;
        }

        .buy-button button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buy-button button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h2>Complete Your Payment for Membership</h2>
    <form action="" method="POST">
        <input type="hidden" name="request_type" value="renewal">
        <label for="location">Delivery Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="bank_account">Bank Account Number:</label>
        <input type="text" id="bank_account" name="bank_account" required 
        pattern="^(?=.*\d)[A-Za-z0-9]+$" 
        title="Bank account number must contain at least one number and may include letters, but cannot be only letters.">

        <div class="total-price">Total: Rs <?php echo number_format($product_price_renewal, 2); ?></div>

        <div class="free-delivery">Free Delivery</div>

        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
