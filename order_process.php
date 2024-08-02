<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost"; // Change if using a different server
$username = "root";
$password = "";
$dbname = "library";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $Bno = isset($_POST['Bno']) ? $conn->real_escape_string($_POST['Bno']) : '';
    $Bname = isset($_POST['Bname']) ? $conn->real_escape_string($_POST['Bname']) : '';
    $Author = isset($_POST['Author']) ? $conn->real_escape_string($_POST['Author']) : '';
    $UserName = isset($_POST['UserName']) ? $conn->real_escape_string($_POST['UserName']) : '';
    $Mob_No = isset($_POST['Mob_No']) ? $conn->real_escape_string($_POST['Mob_No']) : '';

    // Validate required fields
    if (!empty($Bno) && !empty($UserName) && !empty($Mob_No)) {
        // Calculate the return date (14 days from today)
        $currentDate = date('Y-m-d');
        $returnDate = date('Y-m-d', strtotime($currentDate . ' + 14 days'));

        // Format return date to dd-mm-yyyy
        $formattedReturnDate = date('d-m-Y', strtotime($returnDate));

        // Update book status to 'OUT'
        $sql = "UPDATE books SET Status = 'OUT' WHERE Bno = '$Bno'";
        if ($conn->query($sql) === TRUE) {
            // Insert order into orders table with return date
            $order_sql = "INSERT INTO orders (Bno, Bname, Author, UserName, Mob_No, rtn_date) 
                          VALUES ('$Bno', '$Bname', '$Author', '$UserName', '$Mob_No', '$returnDate')";
            if ($conn->query($order_sql) === TRUE) {
                // Prepare success message and redirection
                $message = "Order placed successfully! The return date is $formattedReturnDate.";
                echo "<script>
                        alert('$message');
                        window.location.href = 'search_books.php';
                      </script>";
            } else {
                // Prepare error message for order insertion
                $message = "Error recording order: " . $conn->error;
                echo "<script>
                        alert('$message');
                        window.location.href = 'search_books.php';
                      </script>";
            }
        } else {
            // Prepare error message for book status update
            $message = "Error updating book status: " . $conn->error;
            echo "<script>
                    alert('$message');
                    window.location.href = 'search_books.php';
                  </script>";
        }
    } else {
        // Prepare validation error message
        $message = "Please fill in all required fields.";
        echo "<script>
                alert('$message');
                window.location.href = 'search_books.php';
              </script>";
    }
} else {
    // Prepare invalid request method message
    $message = "Invalid request method.";
    echo "<script>
            alert('$message');
            window.location.href = 'search_books.php';
          </script>";
}

// Close connection
$conn->close();
?>
