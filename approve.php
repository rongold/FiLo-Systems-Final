<?php

session_start();

include 'config.php';

// Check if the user is already logged in, if so then redirect him to admin page
if (!isset($_SESSION['username']) OR !($_SESSION["is_admin"] == 1)) {
  header('location:adminLogin.php');
}
//get Id from the button
$requestId = isset($_GET['id']) ? $_GET['id'] : '';

// Insert statement with id as a parameter
$sql = "UPDATE requests SET is_approved=1 WHERE requestId=$requestId";

if($stmt = mysqli_prepare($link, $sql)){

  // Attempt to execute the prepared statement
  if(mysqli_stmt_execute($stmt)){
    // Redirect back to the list page
    header("location: requestList.php");
  } else{
    echo "Something went wrong. Please try again later.";
  }

  // Close statement
  mysqli_stmt_close($stmt);
}
?>
