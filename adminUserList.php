<?php

session_start();

include 'config.php';


// Check if the user is already logged in, if not then redirect him to login page
if (!isset($_SESSION['username']) OR !($_SESSION["is_admin"] == 1)) {
  header('location:adminLogin.php');
}

?>
 <!DOCTYPE html>
 <html >
   <head>
     <title>User List</title>
     <link rel="stylesheet" type="text/css" href="stylesheet.css">
     <?php include 'links.php'?>
   </head>
   <body>
     <div class="topnav">
       <a href="admin.php">Home</a>
       <a class="active" href="adminUserList.php">User List</a>
       <a href="requestList.php">Request</a>
       <a href="logout.php">Logout</a>
     </div>
     <div class="container">
     <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
   </div>
   <div class="container">
     <?php
     // Selects all non admin accounts
     $sql = "SELECT accountId, username, email, created_at FROM users WHERE is_admin='0' ";
     if($result = mysqli_query($link, $sql)){
       if(mysqli_num_rows($result) > 0){
         echo '<span style="styleone.css">';
         echo "<table>";
         echo "<tr>";
         echo "<th>id</th>";
         echo "<th>Username</th>";
         echo "<th>Email</th>";
         echo "<th>Created At</th>";
         echo "<th>Edit User</th>";
         echo "</tr>";
         while($row = mysqli_fetch_array($result)){
           $id=$row['accountId'];
           $user=$row['username'];
           echo "<tr>";
           echo "<td>" . $row['accountId'] . "</td>";
           echo "<td>" . $row['username'] . "</td>";
           echo "<td>" . $row['email'] . "</td>";
           echo "<td>" . $row['created_at'] . "</td>";
           //creates button with variables of id and username to page editing the user details
           echo  "<td> <button class='btn btn-dark'> <a href='edit.php?id=".$id."&user=".$user."' class='text-white'> Edit </a> </button> </td>";
           echo "</tr>";
         }
         echo "</table>";
         echo "</span>";

         // Free result set
         mysqli_free_result($result);
       } else{
         echo "No records matching your query were found.";
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
