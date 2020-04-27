<?php
session_start();

include 'config.php';

//checks if the user has been set
if($_SESSION['username']==null){
  header("location: login.php");
}

?>
<!DOCTYPE html>
<html >
<head>
  <title>Ongoing Requests</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <?php include 'links.php'?>
</head>
<body>
  <div class="topnav">
    <a href="registered.php">Home</a>
    <a href="add.php">Add</a>
    <a class="active" href="activeRequests.php">Ongoing Requests</a>
    <a href="reset.php">Reset Password</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
  </div>
  <div class="container">
    <?php
    // Select Statement for the current users requests ongoing or approved
    $sql = "SELECT requestId, itemsId, reason, is_approved FROM requests WHERE usersId = ".$_SESSION['accountId']." AND (is_approved = 1 OR is_approved IS NULL)";
    if($result = mysqli_query($link, $sql)){
      if(mysqli_num_rows($result) > 0){
        //Table is created in the echo
        echo '<span style="styleone.css">';
        echo "<table>";
        echo "<tr>";
        echo "<th>Request Id</th>";
        echo "<th>Item Id</th>";
        echo "<th>Reason</th>";
        echo "<th>Approved</th>";
        echo "</tr>";
        while($row = mysqli_fetch_array($result)){
          //checks the rows' is_approved value
          $approved="";
          if($row['is_approved']==null){
            $approved="Not Approved";
          }else if($row['is_approved']==0){
            $approved="Rejected";
          }else if($row['is_approved']==1){
            $approved="Approved";
          }

          echo "<tr>";
          echo "<td>" . $row['requestId'] . "</td>";
          echo "<td>" . $row['itemsId'] . "</td>";
          echo "<td>" . $row['reason'] . "</td>";
          echo "<td>" . $approved . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        echo "</span>";

        // Free result set
        mysqli_free_result($result);
      } else{
        echo "You have no outgoing request.";
      }
    } else{
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    // Close connection
    mysqli_close($link);
    ?>
  </div>
</body>
</html>
