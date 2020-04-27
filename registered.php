<?php
session_start();

include 'config.php';
if($_SESSION['username']==null){
  header("location: login.php");
}

?>
<!DOCTYPE html>
<html >
<head>
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <?php include 'links.php'?>
</head>
<body>
  <div class="topnav">
    <a class="active" href="registered.php">Home</a>
    <a href="add.php">Add</a>
    <a href="activeRequests.php">Ongoing Requests</a>
    <a href="reset.php">Reset Password</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
  </div>
  <div class="container">
    <?php
    // Select all items
    $sql = "SELECT itemId, category, colour, date, photo, place, description  FROM items";
    if($result = mysqli_query($link, $sql)){
      if(mysqli_num_rows($result) > 0){
        echo '<span style="styleone.css">';
        echo "<table>";
        echo "<tr>";
        echo "<th>id</th>";
        echo "<th>category</th>";
        echo "<th>colour</th>";
        echo "<th>date</th>";
        echo "<th>photo</th>";
        echo "<th>place</th>";
        echo "<th>description</th>";
        echo "<th>Request Item</th>";
        echo "</tr>";
        while($row = mysqli_fetch_array($result)){
          $id=$row['itemId'];
          echo "<tr>";
          echo "<td>" . $row['itemId'] . "</td>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['colour'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td><img src=" . $row['photo'] . " height='100px' width='100px'></td>";
          echo "<td>" . $row['place'] . "</td>";
          echo "<td>" . $row['description'] . "</td>";
          //button that goes to request.php with the id of the item being requested
          echo  "<td> <button class='btn btn-dark'> <a href='request.php?id=".$id." ' class='text-white'> Request </a> </button> </td>";
          echo "</tr>";
        }
        echo "</table>";
        echo "</span>";

        // Free result set
        mysqli_free_result($result);
      } else{
        echo "No Items in the database.";
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
