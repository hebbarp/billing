<?php
// Start the session to access the selected books and purchaser details
session_start();

// Retrieve the selected books and purchaser details from the session
$selectedBooks = isset($_SESSION['selected_books']) ? $_SESSION['selected_books'] : [];
$purchaserDetails = isset($_SESSION['purchaser_details']) ? $_SESSION['purchaser_details'] : [];

print_r($selectedBooks);
// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=kasapa', 'dbuser', 'Db@12082022');

// Calculate the total price of the selected books
$totalPrice = 0;

// Apply the discount
// $discount = $purchaserDetails['discount'] / 100;
// $discountedPrice = $totalPrice * (1 - $discount);




// Calculate the final price with GST
// $gstRate = 0.18; // GST rate is 18%
// $finalPrice = $discountedPrice * (1 + $gstRate);

// Generate invoice ID
$invoiceId = 'INV-' . date('YmdHis');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        /* CSS styles here */
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="invoice-box">
        <!-- Invoice table -->
        <table>
            <tr class="heading">
                <th colspan="2">Invoice</th>
                <th>Date: <?php echo date('Y-m-d'); ?></th>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Invoice ID:</strong> <?php echo $invoiceId; ?><br>
                    <strong>Customer Name:</strong> <?php echo $purchaserDetails['name']; ?><br>
                    <strong>Address:</strong> <?php echo $purchaserDetails['address']; ?><br>
                    <strong>GST Number:</strong> <?php echo $purchaserDetails['gst']; ?><br>
                    <strong>Membership Number:</strong> <?php echo $purchaserDetails['membership']; ?>
                </td>
                <td>
                    <!-- Additional details if needed -->
                </td>
            </tr>
            <tr class="heading">
                <th>Book Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php foreach ($selectedBooks as $bookId => $book):
                    echo print_r($book);
                    $copies = $book['copies'];
                    $discount = $book['discount'];                

                    // Query the book from the database
                    $sql = 'SELECT * FROM books WHERE id = :bookId';
                    $stmt = $db->prepare($sql);
                    $stmt->execute([':bookId' => $bookId]);
                    $bookData = $stmt->fetch(PDO::FETCH_ASSOC);                

                    // Calculate the price for this book
                    var_dump($bookData);
                    $bookPrice = $bookData['bookPrice'];
                    print($bookPrice);
                    $bookTotalPrice = $bookPrice * $copies * (1 - ($discount / 100));
                    $totalPrice += $bookTotalPrice;                

                    // Store the calculated price in the book array
                    //$selectedBooks[$bookId]['totalPrice'] = $bookTotalPrice;
                ?>
                <tr class="item">                  
                    <td><?php echo $book['bookName']; ?></td>
                    <td><?php echo $book['copies']; ?></td>
                    <td><?php echo $bookPrice; ?></td>
                    <td><?php echo $discount; ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr class="total">
                      <td colspan="2"></td>
                      <td>Total: <?php echo $totalPrice; ?></td>
                    </tr>
                    <tr class="total">
                      <td colspan="2"></td>
                      <td>Discount: <?php echo ($discount * 100) . '%'; ?></td>
                    </tr>
                    <tr class="total">
                      <td colspan="2"></td>
                      <td>Discounted Price (without GST): <?php echo $discountedPrice; ?></td>
                    </tr>
                <tr class="total">
                      <td colspan="2"></td>
                      <td>Final Price (with GST): <?php echo $finalPrice; ?></td>
                </tr>
        </table>                    

                    <!-- Additional invoice content if needed -->                    

    </div>
</body>
</html>
