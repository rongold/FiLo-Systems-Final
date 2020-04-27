<?php
session_start();

include 'config.php';

// Define variables and initialize with empty values
$reason ="";
$reason_err = "";
//get id from page
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


  if (empty($_POST["reason"])) {
    $reason_err = "Reason is required";
  }else {
    $reason = trim($_POST["reason"]);
  }

  // Check input errors before inserting in database
  if(empty($reason_err)){

    $itemId=$_POST["itemId"];

    // Insert statement from form
      $sql = "INSERT INTO requests (itemsId, usersId, reason) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "iis", $param_itemId, $param_userId, $param_reason);

      // Set parameters
      $param_userId = $_SESSION['accountId'];
      $param_itemId = $itemId;
      $param_reason = $reason;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // Redirect to main registered page
        header("location: registered.php");
      } else{
        echo "Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <div class="topnav">
    <a href="registered.php">Home</a>
    <a href="add.php">Add</a>
    <a  class="active" href="request.php">Request</a>
    <a href="activeRequests.php">Ongoing Requests</a>
    <a href="reset.php">Reset Password</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
  </div>
  <div class="wrapper">
    <h2>Request a Item</h2>
    <p>Please fill this form to submit a request.</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Reason: <textarea name="reason" rows="5" cols="40"><?php echo $reason;?></textarea>
      <span class="error"><?php echo $reason_err;?></span>
      <br><br>
      <input type="submit" class="btn btn-default" name="submit" value="Submit">
      <input type="reset" class="btn btn-default" value="Reset">
      <input type="hidden" name="itemId" value="<?php echo $id?>">
    </form>
  </div>
</body>
</html>
