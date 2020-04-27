<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="utf-8">
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <?php include 'links.php'?>
</head>
<body>
  <div class="topnav">
    <a class="active" href="public.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="adminLogin.php">Admin Login</a>
  </div>
  <div class="wrapper">
  <?php
  //Selects all the items
  $sql = "SELECT itemId, category, colour, date FROM items";
  if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
      //display all results in a table
      echo '<span style="styleone.css">';
      echo "<table>";
      echo "<tr>";
      echo "<th>Id</th>";
      echo "<th>Category</th>";
      echo "<th>Colour</th>";
      echo "<th>Date</th>";
      echo "</tr>";
      while($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td>" . $row['itemId'] . "</td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "<td>" . $row['colour'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "</span>";
      // Free result set
      mysqli_free_result($result);
    } else{
      echo "No items in database.";
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
