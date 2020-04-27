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
     <title>Request</title>
     <link rel="stylesheet" type="text/css" href="stylesheet.css">
     <?php include 'links.php'?>
   </head>
   <body>
     <div class="topnav">
       <a href="admin.php">Home</a>
       <a href="adminUserList.php">User List</a>
       <a class="active" href="requestList.php">Request</a>
       <a href="logout.php">Logout</a>
     </div>
     <div class="container">
     <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
   </div>
   <div class="container">
     <?php
     //Select all reasons that have not been approved
     $sql = "SELECT requestId, itemsId, usersId, reason, is_approved FROM requests WHERE is_approved IS NULL";
     if($result = mysqli_query($link, $sql)){
       if(mysqli_num_rows($result) > 0){
         echo '<span style="styleone.css">';
         echo "<table>";
         echo "<tr>";
         echo "<th>Request Id</th>";
         echo "<th>Items Id</th>";
         echo "<th>User Id</th>";
         echo "<th>Reason</th>";
         echo "<th>Approved</th>";
         echo "<th>Approve</th>";
         echo "<th>Reject</th>";
         echo "</tr>";
         while($row = mysqli_fetch_array($result)){
           $approved="";
           if($row['is_approved']==null){
             $approved="Not Approved";
           }else if($row['is_approved']==0){
             $approved="Rejected";
           }else if($row['is_approved']==1){
             $approved="Approved";
           }
           $id=$row['requestId'];
           echo "<tr>";
           echo "<td>" . $row['requestId'] . "</td>";
           echo "<td>" . $row['itemsId'] . "</td>";
           echo "<td>" . $row['usersId'] . "</td>";
           echo "<td>" . $row['reason'] . "</td>";
           echo "<td>" . $approved . "</td>";
           echo  "<td> <button class='btn btn-success'> <a href='approve.php?id=".$id."' class='text-white'> Approve </a> </button> </td>";
           echo  "<td> <button class='btn btn-danger'> <a href='reject.php?id=".$id."' class='text-white'> Reject </a> </button> </td>";
           echo "</tr>";
         }
         echo "</table>";
         echo "</span>";

         // Free result set
         mysqli_free_result($result);
       } else{
         echo "No requests to approve.";
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
