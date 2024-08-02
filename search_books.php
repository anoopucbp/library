<!DOCTYPE html>
<html lang="ml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <style>
        body {
            font-family: 'Noto Sans Malayalam', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Search Books</h2>

    <?php
    // Ensure UTF-8 content type
    header('Content-Type: text/html; charset=UTF-8');

    if (isset($_GET['message'])) {
        echo "<div class='message'>" . htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') . "</div>";
    }
    ?>

    <form action="search_books.php" method="GET">
        <label for="search">Search by Book Name or Author:</label>
        <input type="text" id="search" name="search" placeholder="Enter keywords..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <br><br>
        <label for="category">Filter by Category:</label>
        <select id="category" name="category">
            <option value="">All Categories</option>
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "library";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Set character set to UTF-8
                $conn->set_charset("utf8mb4");

                $sql = "SELECT DISTINCT Category FROM books";
                $result = $conn->query($sql);

                if ($result === false) {
                    echo "<option value=''>Error fetching categories</option>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $categoryName = $row['Category'];
                        $selected = (isset($_GET['category']) && $_GET['category'] === $categoryName) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') . "' $selected>$categoryName</option>";
                    }
                }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Search">
    </form>

    <hr>

    <?php
    if (isset($_GET['search']) || isset($_GET['category'])) {
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
        $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

        $sql = "SELECT * FROM books WHERE 1";

        if (!empty($search)) {
            $sql .= " AND (Bname LIKE '%$search%' OR Author LIKE '%$search%' OR Bno LIKE '%$search%')";
        }
        if (!empty($category)) {
            $sql .= " AND Category = '$category'";
        }

        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error executing the query: " . $conn->error;
        } else {
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Book Number</th><th>Book Name</th><th>Author</th><th>Category</th><th>Publisher</th><th>Price</th><th>Rack</th><th>Status</th><th>Order/Receive</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $Bno = $row["Bno"];
                    $orderCheckSql = "SELECT COUNT(*) AS OrderCount FROM orders WHERE BookID = '$Bno' AND OrderStatus = 'Active'";
                    $orderCheckResult = $conn->query($orderCheckSql);
                    $isOrdered = ($orderCheckResult && $orderCheckResult->fetch_assoc()["OrderCount"] > 0);

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["Bno"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Bname"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Author"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Category"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Publisher"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Price"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Rack"], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Status"], ENT_QUOTES, 'UTF-8') . "</td>";
                    
                    if ($row["Status"] == 'IN' && !$isOrdered) {
                        echo "<td><a href='order_form.php?Bno=" . urlencode($row["Bno"]) . "&Bname=" . urlencode($row["Bname"]) . "&Author=" . urlencode($row["Author"]) . "'>Order</a></td>";
                    } elseif ($row["Status"] == 'OUT') {
                        echo "<td><a href='receive_book.php?Bno=" . urlencode($row["Bno"]) . "'>Receive Back</a></td>";
                    } else {
                        echo "<td>Unavailable</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No results found";
            }
        }
    }

    $conn->close();
    ?>
</body>
</html>
