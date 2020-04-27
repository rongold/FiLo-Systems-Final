<?php
session_start();

include 'config.php';

//checks if the user is an admin user and if not redirects back to login page
if (!isset($_SESSION['username']) OR !($_SESSION["is_admin"] == 1)) {
  header('location:adminLogin.php');
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
       <a class="active" href="admin.php">Home</a>
       <a href="adminUserList.php">User List</a>
       <a href="requestList.php">Request</a>
       <a href="logout.php">Logout</a>
     </div>
     <div class="container">
     <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
   </div>
   <div class="container">
     <?php
     // Select query for the all the items
     $sql = "SELECT itemId, category, colour, date, photo, place, description  FROM items";
     if($result = mysqli_query($link, $sql)){
       if(mysqli_num_rows($result) > 0){
         //creates a table with the data
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
         echo "<th>Edit</th>";
         echo "</tr>";
         while($row = mysqli_fetch_array($result)){
           //stores the value of itemId of that row in a local variable
           $id=$row['itemId'];
           echo "<tr>";
           echo "<td>" . $row['itemId'] . "</td>";
           echo "<td>" . $row['category'] . "</td>";
           echo "<td>" . $row['colour'] . "</td>";
           echo "<td>" . $row['date'] . "</td>";
           echo "<td><img src=" . $row['photo'] . " height='100px' width='100px'></td>";
           echo "<td>" . $row['place'] . "</td>";
           echo "<td>" . $row['description'] . "</td>";
           //creates a button that goes to another page with local variable as a Get
           echo  "<td> <button class='btn btn-dark'> <a href='editItem.php?id=".$id." ' class='text-white'> Edit </a> </button> </td>";
           echo "</tr>";
         }
         echo "</table>";
         echo "</span>";

         // Free result set
         mysqli_free_result($result);
       } else{
         echo "There are no items in the database.";
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
