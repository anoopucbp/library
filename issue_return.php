<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$Bno = isset($_GET['Bno']) ? $conn->real_escape_string($_GET['Bno']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = isset($_POST['OrderID']) ? intval($_POST['OrderID']) : 0;
    $Bno = $conn->real_escape_string($_POST['Bno']);
    $Bname = $conn->real_escape_string($_POST['Bname']);
    $Author = $conn->real_escape_string($_POST['Author']);
    $UserName = $conn->real_escape_string($_POST['UserName']);
    $Mob_No = $conn->real_escape_string($_POST['Mob_No']);
    $OrderDate = $conn->real_escape_string($_POST['OrderDate']);
    $rtn_date = $conn->real_escape_string($_POST['rtn_date']);
    $assigned_to = $conn->real_escape_string($_POST['assigned_to']);
    $Status = $conn->real_escape_string($_POST['Status']);
    $D_date = $conn->real_escape_string($_POST['D_date']);
    $Remarks = $conn->real_escape_string($_POST['Remarks']);
    $UserEmail = $conn->real_escape_string($_POST['UserEmail']);

    if ($orderID > 0) {
        // Update order
        $sql = "UPDATE orders SET Bno='$Bno', Bname='$Bname', Author='$Author', UserName='$UserName', Mob_No='$Mob_No', OrderDate='$OrderDate', rtn_date='$rtn_date', assigned_to='$assigned_to', Status='$Status', D_date='$D_date', Remarks='$Remarks', UserEmail='$UserEmail' WHERE OrderID='$orderID'";
    } else {
        // Insert new order
        $sql = "INSERT INTO orders (Bno, Bname, Author, UserName, Mob_No, OrderDate, rtn_date, assigned_to, Status, D_date, Remarks, UserEmail) VALUES ('$Bno', '$Bname', '$Author', '$UserName', '$Mob_No', '$OrderDate', '$rtn_date', '$assigned_to', '$Status', '$D_date', '$Remarks', '$UserEmail')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record saved successfully.'); window.location.href='search_books.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='search_books.php';</script>";
    }
}

// Fetch book details
$book = $conn->query("SELECT * FROM books WHERE Bno='$Bno'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issue/Return Book</title>
</head>
<body>
    <h1>Issue/Return Book</h1>

    <?php if ($book && $book['Status'] == 'out'): ?>
        <p>Sorry, this book is currently out and cannot be issued.</p>
    <?php else: ?>
        <form action="issue_return.php" method="post">
            <input type="hidden" name="OrderID" id="OrderID">
            <label for="Bno">Book Number:</label>
            <input type="text" name="Bno" id="Bno" value="<?php echo htmlspecialchars($book['Bno']); ?>" required readonly><br>
            <label for="Bname">Book Name:</label>
            <input type="text" name="Bname" id="Bname" value="<?php echo htmlspecialchars($book['Bname']); ?>" required readonly><br>
            <label for="Author">Author:</label>
            <input type="text" name="Author" id="Author" value="<?php echo htmlspecialchars($book['Author']); ?>" required readonly><br>
            <label for="UserName">User Name:</label>
            <input type="text" name="UserName" id="UserName" required><br>
            <label for="Mob_No">Mobile Number:</label>
            <input type="text" name="Mob_No" id="Mob_No" required><br>
            <label for="OrderDate">Order Date:</label>
            <input type="datetime-local" name="OrderDate" id="OrderDate" required><br>
            <label for="rtn_date">Return Date:</label>
            <input type="date" name="rtn_date" id="rtn_date"><br>
            <label for="assigned_to">Assigned To:</label>
            <input type="text" name="assigned_to" id="assigned_to"><br>
            <label for="Status">Status:</label>
            <input type="text" name="Status" id="Status" required><br>
            <label for="D_date">Delivery Date:</label>
            <input type="date" name="D_date" id="D_date"><br>
            <label for="Remarks">Remarks:</label>
            <input type="text" name="Remarks" id="Remarks"><br>
            <label for="UserEmail">User Email:</label>
            <input type="email" name="UserEmail" id="UserEmail"><br>
            <button type="submit">Save</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
