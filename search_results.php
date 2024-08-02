<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <style>
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
</head>
<body>
    <h2>Search Books</h2>

    <?php
    // Display a success message if redirected with a message
    if (isset($_GET['message'])) {
        echo "<div class='message'>" . htmlspecialchars($_GET['message']) . "</div>";
    }
    ?>

    <form action="search_books.php" method="GET">
        <label for="search">Search by Book Name or Author:</label>
        <input type="text" id="search" name="search" placeholder="Enter keywords..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <br><br>
        <label for="category">Filter by Category:</label>
        <select id="category" name="category">
            <option value="">All Categories</option>
            <?php
                // Establish database connection (replace with your credentials)
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

                // SQL query to fetch distinct categories from your books table
                $sql = "SELECT DISTINCT Category FROM books";
                $result = $conn->query($sql);

                // Check if query returned results
                if ($result === false) {
                    echo "<option value=''>Error fetching categories</option>";
                } else {
                    // Output options for each category
                    while ($row = $result->fetch_assoc()) {
                        $categoryName = $row['Category'];
                        $selected = (isset($_GET['category']) && $_GET['category'] === $categoryName) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($categoryName) . "' $selected>$categoryName</option>";
                    }
                }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Search">
    </form>

    <hr>

    <?php
    // Check if form has been submitted
    if (isset($_GET['search']) || isset($_GET['category'])) {
        // Prepare SQL statement based on form input
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
        $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

        // Build the initial SQL query
        $sql = "SELECT * FROM books WHERE 1";

        // Append conditions based on search and category
        if (!empty($search)) {
            $sql .= " AND (Bname LIKE '%$search%' OR Author LIKE '%$search%')";
        }
        if (!empty($category)) {
            $sql .= " AND Category = '$category'";
        }

        // Execute the query
        $result = $conn->query($sql);

        // Check if query returned results
        if ($result === false) {
            echo "Error executing the query: " . $conn->error;
        } else {
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Book Number</th><th>Book Name</th><th>Author</th><th>Category</th><th>Publisher</th><th>Price</th><th>Rack</th><th>Status</th><th>Order</th></tr>";
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    // Check if the book is already ordered
                    $Bno = $row["Bno"];
                    $orderCheckSql = "SELECT COUNT(*) AS OrderCount FROM orders WHERE BookID = '$Bno' AND OrderStatus = 'Active'";
                    $orderCheckResult = $conn->query($orderCheckSql);
                    $isOrdered = ($orderCheckResult && $orderCheckResult->fetch_assoc()["OrderCount"] > 0);

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["Bno"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Bname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Publisher"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Rack"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Stats"]) . "</td>";
                    
                    if ($row["Stats"] == 'IN' && !$isOrdered) {
                        echo "<td><a href='order_form.php?Bno=" . urlencode($row["Bno"]) . "&Bname=" . urlencode($row["Bname"]) . "&Author=" . urlencode($row["Author"]) . "'>Order</a></td>";
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

    // Close connection
    $conn->close();
    ?>
</body>
</html>
