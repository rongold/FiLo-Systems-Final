<?php

session_start();

include 'config.php';


if (!isset($_SESSION['username']) OR !($_SESSION["is_admin"] == 1)) {
  header('location:adminLogin.php');
}

$requestId = $_GET['id'];

// Update statement based on the id gotten
$sql = "UPDATE requests SET is_approved=0 WHERE requestId=$requestId";

if($stmt = mysqli_prepare($link, $sql)){

  // Attempt to execute the prepared statement
  if(mysqli_stmt_execute($stmt)){
    // Redirect to list page
    header("location: requestList.php");
  } else{
    echo "Something went wrong. Please try again later.";
  }

  // Close statement
  mysqli_stmt_close($stmt);
}
?>
