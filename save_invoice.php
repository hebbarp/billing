<?
session_start();
//$selectedBooks = isset($_SESSION['selected_books']) ? $_SESSION['selected_books'] : [];
$purchaserDetails = isset($_SESSION['purchaser_details']) ? $_SESSION['purchaser_details'] : [];

// error_reporting(E_ALL);
//   ini_set('display_errors', 1);

// Establish a database connection
$db = new PDO('mysql:host=localhost;dbname=kasapa', 'dbuser', 'Db@12082022');

// Retrieve necessary data from the invoice
$invoiceID = $_POST['invoiceId']; // Assuming you have a function for generating the invoice ID
$finalPrice = $_POST['finalPrice'];
$purchaserDetails = $_SESSION['purchaser_details']; // Assuming purchaser details are stored in a session
$selectedBooksResult = $_SESSION['selected_books_result']; // Assuming selected books are stored in a session
$invoiceDate = date("F j, Y");

// Prepare the INSERT statement
$sql = 'INSERT INTO invoices2 (invoiceId, purchaserName, purchaserAddress, totalPrice, invoiceDate) VALUES (:invoiceID, :name, :address, :totalPrice, :invoiceDate)';
$stmt = $db->prepare($sql);
$stmt->bindParam(':invoiceID', $invoiceID);
$stmt->bindParam(':name', $purchaserDetails['name']);
$stmt->bindParam(':address', $purchaserDetails['address']);
$stmt->bindParam(':totalPrice', $finalPrice);
$stmt->bindParam(':invoiceDate', $invoiceDate);

// Execute the SQL statement
if ($stmt->execute()) {
    // Invoice stored successfully
    echo "Invoice stored successfully!";
} else {
    // Error occurred while storing the invoice
    echo "Error storing the invoice.";
}
?>