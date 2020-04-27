  <?php
  session_start();

  include 'config.php';

  // Define variables and initialize with empty values
  $username = $password = $is_admin = "";
  $username_err = $password_err = $is_admin_err="";


  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){

      // Check if username is empty
      if(empty(trim($_POST["username"]))){
          $username_err = "Please enter username.";
      } else{
          $username = trim($_POST["username"]);
      }

      // Check if password is empty
      if(empty(trim($_POST["password"]))){
          $password_err = "Please enter your password.";
      } else{
          $password = trim($_POST["password"]);
      }

      // checks if no errors
      if(empty($username_err) && empty($password_err)){
          // Prepare a select statement of the user
          $sql = "SELECT accountId, username, password, is_admin FROM users WHERE username = ?";

          if($stmt = mysqli_prepare($link, $sql)){
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "s", $param_username);

              // Set parameters
              $param_username = $username;

              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt)){
                  // Store result
                  mysqli_stmt_store_result($stmt);

                  // Check if username exists, if so then verify password
                  if(mysqli_stmt_num_rows($stmt) == 1){
                      // Bind result variables
                      mysqli_stmt_bind_result($stmt, $accountId, $username, $hashed_password,$is_admin);
                      if(mysqli_stmt_fetch($stmt)){
                        // checks if password is correct
                          if(password_verify($password, $hashed_password)){
                            //checks if account is an admin account, if so creats a session
                              if($is_admin!=0){
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["accountId"] = $accountId;
                                $_SESSION["username"] = $username;
                                $_SESSION["is_admin"] = $is_admin;

                                // Redirect user to admin page
                                header("location: admin.php");
                              }else{
                                $is_admin_err="The account is not an admin account";
                              }

                          } else{
                              // Display an error message if password is not valid
                              $password_err = "The password you entered was not valid.";
                          }
                      }
                  } else{
                      // Display an error message if username doesn't exist
                      $username_err = "No account found with that username.";
                  }
              } else{
                  echo "Oops! Something went wrong. Please try again later.";
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
      <title>Admin Login</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
    <div class="topnav">
      <a href="public.php">Home</a>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
      <a class="active" href="adminLogin.php">Admin Login</a>
    </div>
      <div class="wrapper">
          <h2>Admin Login</h2>
          <p>Please fill in your credentials to login.</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                  <span class="help-block"><?php echo $username_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control">
                  <span class="help-block"><?php echo $password_err; ?></span>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Login">
              </div>
          </form>
      </div>
  </body>
  </html>
