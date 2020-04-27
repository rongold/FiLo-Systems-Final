<?php
session_start();

include 'config.php';


if($_SESSION['username']==null){
  header("location: login.php");
}
//cleans the input data
function clean($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Define variables and initialize with empty values
$category = $colour = $date = $photo = $place= $description = $image = $location = "";
$category_err = $colour_err = $date_err = $photo_err = $place_err = $description_err = $image_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  if (empty($_POST["category"])) {
    $category_err = "Category is required";
  }else {
    $category = clean($_POST["category"]);
  }

  if (empty($_POST["colour"])) {
    $colour_err = "Colour is required";
  }else {
    $colour = filter_var(clean($_POST["colour"]), FILTER_SANITIZE_STRING);
  }

  if (empty($_POST["date"])) {
    $date_err = "Date is required";
  }else {
    $date = clean($_POST["date"]);
  }

  if (empty($_POST["description"])) {
    $description_err= "Description is required";
  }else {
    $description = filter_var(clean($_POST["description"]), FILTER_SANITIZE_STRING);
  }

  if (empty($_POST["place"])) {
    $place_err= "Place is required";
  }else {
    $place = filter_var(clean($_POST["place"]), FILTER_SANITIZE_STRING);

  }

  if (empty($_FILES['file'])) {
    $image_err= "Image is required";
  }else {
    $image = $_FILES['file'];

    $fileName = $image['name'];
    $fileError = $image['error'];
    $fileTmp = $image['tmp_name'];

    $fileext = explode('.',$fileName);
    $fileCheck = strtolower(end($fileTmp));

    $fileType = array('png','jpg','jpeg');
    //checks if file is the right type
    if (in_array($fileCheck,$fileType)) {
      $location = 'upload/'.$fileName ;
      move_uploaded_file($fileTmp,$location);
    }else{
      $image_err="Png, Jgp or Jpeg file type is required.";
    }
  }



  // Check input errors before inserting in database
  if(empty($category_err) && empty($colour_err) && empty($date_err) && empty($description_err) && empty($place_err) && empty($image_err) ){

    // Prepare an insert statement for the items
    $sql = "INSERT INTO items (FoundUserId, category, colour, date, place, description, photo) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "issssss", $param_foundUserId, $param_category, $param_colour, $param_date, $param_place, $param_description, $image_image);

      // Set parameters
      $param_foundUserId = $_SESSION['accountId'];
      $param_category = $category;
      $param_colour = $colour;
      $param_date = $date;
      $param_place = $place;
      $param_description = $description;
      $param_image=$location;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // Redirect to registered page
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
  <title>Add</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <div class="topnav">
    <a href="registered.php">Home</a>
    <a class="active" href="add.php">Add</a>
    <a href="activeRequests.php">Ongoing Requests</a>
    <a href="reset.php">Reset Password</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="container">
    <h2 class="text-center text-dark"> Welcome <?php echo $_SESSION['username']; ?> </h2>
  </div>
  <div class="wrapper">
    <h2>Add a new Item</h2>
    <p>Please fill this form to submit a new item.</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
      Category:
      <input type="radio" name="category" <?php if (isset($category) && $category=="pets") echo "checked";?> value="pets">Pets
      <input type="radio" name="category" <?php if (isset($category) && $category=="phones") echo "checked";?> value="phones">Phones
      <input type="radio" name="category" <?php if (isset($category) && $category=="jewellery") echo "checked";?> value="jewellery">Jewellery
      <span class="error">* <?php echo $category_err;?></span>
      <br><br>
      Colour: <input type="text" name="colour" value="<?php echo $colour;?>">
      <span class="error">* <?php echo $colour_err;?></span>
      <br><br>
      Date: <input type="date" name="date" value="<?php echo $date;?>" min="2010-01-01" max="2020-06-30">
      <span class="error">* <?php echo $date_err;?></span>
      <br><br>
      Place: <input type="text" name="place" value="<?php echo $place;?>">
      <span class="error"><?php echo $place_err;?></span>
      <br><br>
      Description: <textarea name="description" rows="5" cols="40"><?php echo $description;?></textarea>
      <span class="error"><?php echo $description_err;?></span>
      <br><br>
      Image: <input type = "file" name = "file" id="file" value="<?php echo $image;?>">
      <span class="error"><?php echo $image_err;?></span>
      <br><br>
      <input type="submit" class="btn btn-default" name="submit" value="Submit">
      <input type="reset" class="btn btn-default" value="Reset">
      <button class="btn btn-default"> <a href='registered.php'> Cancel </a> </button>
    </form>
  </div>
</body>
</html>
