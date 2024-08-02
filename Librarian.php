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

// Set charset to utf8mb4 for better UTF-8 support
$conn->set_charset("utf8mb4");

// Handle search request
$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
$result = $conn->query("SELECT * FROM books WHERE Bname LIKE '%$search%' OR Author LIKE '%$search%'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Books</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Malayalam', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Search Books</h1>

    <form action="search_books.php" method="post">
        <input type="text" name="search" placeholder="Enter book name or author" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit">Search</button>
    </form>

    <h2>Book List</h2>
    <table>
        <thead>
            <tr>
                <th>Book Number</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Bno'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['Bname'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['Author'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['Status'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (htmlspecialchars($row['Status'], ENT_QUOTES, 'UTF-8') == 'out'): ?>
                            Not available
                        <?php else: ?>
                            <a href="issue_return.php?Bno=<?php echo urlencode(htmlspecialchars($row['Bno'], ENT_QUOTES, 'UTF-8')); ?>">Issue/Return</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
