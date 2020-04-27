<?php
session_start();

$_SESSION = array();
//destroy session
session_destroy();
//return to login page
header('location:login.php');
exit;
 ?>
