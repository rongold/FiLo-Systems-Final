<?php

session_start();

include 'config.php';


//checks if the user is an admin user and if not redirects back to login page
if (!isset($_SESSION['username']) OR !($_SESSION["is_admin"] == 1)) {
  header('location:adminLogin.php');
}

// Define variables and initialize with empty values
$username = $email = "";
$username_err = $email_err = "";

//gets id variable from called page
$accountId = isset($_GET['id']) ? $_GET['id'] : '';

//cleans the input data
function clean($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
  } else{
    //gets username and cleans it
    $username = clean($_POST["username"]);
  }

  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter an email";
  } else {
    $email = clean($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format";
    }
  }
  //checks for errors
  if(empty($username_err) && empty($email_err) ){
    //gets id from form
    $id=$_POST["id"];
    //sql to update field based on form
    $sql = "UPDATE users SET username='$username' , email='$email' WHERE accountId=$id ";
    if(mysqli_query($link, $sql)){
      //if successful returns back to thte list
      header("location: adminUserList.php");
    } else {
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
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
  <title>Edit User</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <div class="topnav">
    <a href="admin.php">Home</a>
    <a href="adminUserList.php">User List</a>
    <a href="requestList.php">Request</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h2 class="text-center text-dark"> Edit Id: <?php echo $_GET['id']?> User: <?php echo $_GET['user']?></h2>
  </div>
  <div class="wrapper">
    <p>Please fill this form to edit a current account.</p>
    <form method="post">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
        <span class="help-block"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
        <label>Email</label>
        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
        <span class="help-block"><?php echo $email_err; ?></span>
      </div>
      <div class="form-group">
        <input type="hidden" value="<?php echo $accountId ?>" name="id">
        <input type="submit" class="btn btn-primary" value="Submit">
        <input type="reset" class="btn btn-default" value="Reset">
      </div>
    </form>
  </div>
</body>
</html>
