<?php
session_start();
// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=kasapa', 'dbuser', 'Db@12082022');

// Handle creating invoice
if (isset($_POST['create_invoice'])) {
    // Get the selected books from the form submission
    $selectedBooks = isset($_POST['selected_books']) ? $_POST['selected_books'] : [];

    // Validate the form
    if (count($selectedBooks) == 0) {
        echo "<script>alert('Please select at least one book.');</script>";
        header("Location: search.php");
        exit();
    }

    // Get the number of copies and discounts for each selected book
    $copies = isset($_POST['copies']) ? $_POST['copies'] : [];
    $discounts = isset($_POST['discounts']) ? $_POST['discounts'] : [];

    // Create an array to store the selected books with additional information
    $selectedBooksArray = [];

    // Iterate over the selected books and add the necessary information to the array
    foreach ($selectedBooks as $bookId) {
        // Get the book details from the database
        $sql = 'SELECT * FROM books WHERE id = :bookId';
        $bookQuery = $db->prepare($sql);
        $bookQuery->execute([':bookId' => $bookId]);
        $book = $bookQuery->fetch(PDO::FETCH_ASSOC);

        // Calculate the final price based on the number of copies and discount
        $finalPrice = $book['bookPrice'] * $copies[$bookId] * (1 - $discounts[$bookId] / 100);

        // Create an array for the selected book with all the required information
        $selectedBook = [
            'bookId' => $bookId,
            'copies' => $copies,
            'discount' => $discounts
        ];

        // Add the selected book to the array
        $selectedBooksArray[] = $selectedBook;
    }

    // Store the selected books array in the session variable
    $_SESSION['selected_books'] = $selectedBooksArray;

    // Redirect to the purchaser details page
    header("Location: purchaser_details.php");
    exit();
}

// Get the search term
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filter the books by search term
if ($search) {
    $sql = 'SELECT * FROM books WHERE bookName LIKE :search OR bookAuthors LIKE :search';
    $books = $db->prepare($sql);
    $books->execute([':search' => '%' . $search . '%']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasapa Inventory Management App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function validateForm() {
            var checkboxes = document.getElementsByName("selected_books[]");
            var checked = false;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    checked = true;
                    break;
                }
            }
            if (!checked) {
                alert("Please select at least one book.");
                return false;
            }
            return true;
        }

         function clearForm() {
            document.getElementById("searchForm").reset();
        }
    </script>
</head>
<body>
    <center><img src="img/logo.jpg" height="200px" align="center"></center>
    <h1>ಕನ್ನಡ ಸಾಹಿತ್ಯ ಪರಿಷತ್ತು</h1>
    <h2>ಪುಸ್ತಕ ಮಾರಾಟ ಮಳಿಗೆ</h2>
    <!-- ul>
        <li><a href="books.php">ಪುಸ್ತಕ ಮಳಿಗೆ</a></li>
        <li><a href="invoices.php">ಬಿಲ್ ನಿರ್ವಹಣೆ</a></li>
    </ul> -->
    <form action="search.php" method="get">
        <input type="text" name="search" placeholder="Search books..." value="<?php echo $search; ?>">
        <input type="submit" value="Search">
    </form>

    <?php if ($search && $books->rowCount() > 0) : ?>
        <h2>Books</h2>
        <form action="search.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Price</th>
                        <th>Copies</th>
                        <th>Discount (%)</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book) : ?>
                        <tr>
                            <td><?php echo $book['bookName']; ?></td>
                            <td><?php echo $book['bookAuthors']; ?></td>
                            <td><?php echo $book['bookPrice']; ?></td>
                            <td>
                                <input type="number" name="copies[<?php echo $book['id']; ?>]" value="1" min="1">
                            </td>
                            <td>
                                <input type="number" name="discounts[<?php echo $book['id']; ?>]" value="0" min="0" max="100">
                            </td>

                            <td>
                                <input type="checkbox" name="selected_books[]" value="<?php echo $book['id']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <input type="submit" name="create_invoice" value="Create Invoice">
            <button onclick="clearForm()">clear</button>


        </form>
    <?php elseif ($search) : ?>
        <p>No books found.</p>
    <?php endif; ?>
</body>
</html>
