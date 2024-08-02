<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Book</title>
    <!-- CSS for styling the form and breadcrumbs -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .breadcrumb {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px 0;
            list-style: none;
            background-color: #e9ecef;
            border-radius: 5px;
            width: 100%;
            max-width: 500px;
            padding-left: 15px;
            box-sizing: border-box;
        }

        .breadcrumb li {
            display: inline;
            font-size: 14px;
        }

        .breadcrumb li + li:before {
            content: ">";
            padding: 0 5px;
            color: #6c757d;
        }

        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="mobile"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="mobile"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .readonly {
            background-color: #f4f4f4;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Breadcrumb Navigation -->
        <ul class="breadcrumb">
            <li><a href="search_books.php">Search Books</a></li>
            <li>Order Book</li>
        </ul>

        <h2>Order Book</h2>
        <form action="order_process.php" method="POST">
            <?php
            // Get book details from URL parameters
            $Bno = isset($_GET['Bno']) ? htmlspecialchars($_GET['Bno']) : '';
            $Bname = isset($_GET['Bname']) ? htmlspecialchars($_GET['Bname']) : '';
            $Author = isset($_GET['Author']) ? htmlspecialchars($_GET['Author']) : '';
            ?>
            <label for="Bno">Book Number:</label>
            <input type="text" id="Bno" name="Bno" value="<?php echo $Bno; ?>" readonly class="readonly">
            
            <label for="Bname">Book Name:</label>
            <input type="text" id="Bname" name="Bname" value="<?php echo $Bname; ?>" readonly class="readonly">
            
            <label for="Author">Author:</label>
            <input type="text" id="Author" name="Author" value="<?php echo $Author; ?>" readonly class="readonly">
            
            <label for="UserName">Your Name:</label>
            <input type="text" id="UserName" name="UserName" placeholder="Enter your name" required>
            
            <label for="Mob_No">Mobile No:</label>
            <input type="mobile" id="Mob_No" name="Mob_No" placeholder="Enter your mobile number" required>
            
            <input type="submit" value="Place Order">
        </form>
    </div>
</body>
</html>
