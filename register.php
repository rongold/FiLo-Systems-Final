<?php
include 'config.php';
// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate username
  if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
  } else{
    // Prepare a select statement
    $sql = "SELECT accountId FROM users WHERE username = ?";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = trim($_POST["username"]);

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // store result
        mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) == 1){
          $username_err = "This username is already taken.";
        } else{
          $username = trim($_POST["username"]);
        }
      } else{
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Validate password
  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter a password.";
  } elseif(strlen(trim($_POST["password"])) < 6){
    $password_err = "Password must have atleast 6 characters.";
  } else{
    $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Please confirm password.";
  } else{
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($password_err) && ($password != $confirm_password)){
      $confirm_password_err = "Password did not match.";
    }
  }
  //Validate Email
  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter an email";
  } else {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format";
    }
  }

  // Check input errors before inserting in database
  if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) ){

    // Insert statement for a new user
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);

      // Set parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
      $param_email = $email;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // Redirect to login page
        header("location: login.php");
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
  <title>Sign Up</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <div class="topnav">
    <a href="public.php">Home</a>
    <a href="login.php">Login</a>
    <a class="active" href="register.php">Register</a>
    <a href="adminLogin.php">Admin Login</a>
  </div>
  <div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
        <input type="reset" class="btn btn-default" value="Reset">
      </div>
    </form>
  </div>
</body>
</html>
