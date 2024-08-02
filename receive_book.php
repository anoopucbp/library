<!DOCTYPE html>
<html lang="ml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Book</title>
    <style>
        body {
            font-family: 'Noto Sans Malayalam', sans-serif;
        }
        .form-container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, button {
            display: block;
            margin: 5px 0;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Receive Book</h2>

    <div class="form-container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $Bno = $conn->real_escape_string($_POST['Bno']);
            $status = 'IN'; // Status to update when receiving the book

            $updateSql = "UPDATE books SET Status = '$status' WHERE Bno = '$Bno'";
            if ($conn->query($updateSql) === TRUE) {
                echo "<p>Book received successfully. <a href='search_books.php?message=Book%20received%20successfully'>Go back to search</a></p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        } else {
            $Bno = htmlspecialchars($_GET['Bno']);
        ?>

        <form action="receive_book.php" method="POST">
            <input type="hidden" name="Bno" value="<?php echo $Bno; ?>">
            <p>Are you sure you want to receive the book with Book Number: <?php echo $Bno; ?> back into the library?</p>
            <button type="submit">Receive Back</button>
        </form>

        <?php
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
