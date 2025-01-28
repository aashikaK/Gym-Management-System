<?php
session_start();
session_unset(); // Unsets all session variables
session_destroy(); // Destroys the session
header("Location: login.php"); // Redirects the user to the login page
exit;
?>
