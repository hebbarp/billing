<?php
session_start();

// Handle purchaser details
if (isset($_POST['submit_details'])) {
    // Validate and sanitize the purchaser details
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $gst = isset($_POST['gst']) ? htmlspecialchars($_POST['gst']) : '';
    $membership = isset($_POST['membership']) ? htmlspecialchars($_POST['membership']) : '';

    // Perform validation checks if necessary

    // Get the purchaser details as an array
    $purchaserDetails = [
        'name' => $name,
        'address' => $address,
        'gst' => $gst,
        'membership' => $membership
    ];

    // Set the purchaser details in the session variable
    $_SESSION['purchaser_details'] = $purchaserDetails;

    // Redirect to the invoice page
    header("Location: invoice.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasapa Inventory Management App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
<body>

  <center><img src="img/logo.jpg" height="200px" align="center"></center>
  <h1>ಕನ್ನಡ ಸಾಹಿತ್ಯ ಪರಿಷತ್ತು</h1>


 <h2>ಗ್ರಾಹಕರ ವಿವರ</h2>

 <form action="purchaser_details.php" method="post">
  <label for="name">ಹೆಸರು:</label><br><br>
  <input type="text" id="name" name="name" required><br><br>
  <label for="address">ವಿಳಾಸ:</label><br><br>
  <input type="text" id="address" name="address" required><br><br>
  <label for="gst">GST ಸಂಖ್ಯೆ:</label><br><br>
  <input type="text" id="gst" name="gst"><br>
  <label for="membership">ಸದಸ್ಯತ್ವ ಸಂಖ್ಯೆ:</label><br><br>
  <input type="text" id="membership" name="membership"><br><br>
  <input type="submit" name="submit_details" value="Submit">
</form>
<br>
<br>
<center>
<a href="search.php"><button>Go Back to Main Page</button></a>
</center>
<br>
<br>
</body>
</html>
