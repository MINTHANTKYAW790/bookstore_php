<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
setcookie('session_token', '', time() - 3600, '/'); // Clear the session token cookie
header("Location: signIn.php");
exit();
