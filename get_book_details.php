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

// Get the Bno from the URL parameter
$Bno = isset($_GET['Bno']) ? $conn->real_escape_string($_GET['Bno']) : '';

$response = ['success' => false];

if (!empty($Bno)) {
    // Log the Bno received
    error_log("Fetching details for Bno: $Bno");

    // SQL query to fetch the book details by Bno
    $sql = "SELECT Bname, Author, Category, Status FROM books WHERE Bno = '$Bno'";
    error_log("SQL Query: $sql");
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch the book details
        $row = $result->fetch_assoc();
        $response = [
            'success' => true,
            'data' => [
                'Bname' => $row['Bname'],
                'Author' => $row['Author'],
                'Category' => $row['Category'],
                'Status' => $row['Status']
            ]
        ];
    } else {
        error_log("No book found for Bno: $Bno");
        $response['message'] = 'Book not found';
    }
} else {
    $response['message'] = 'No Bno provided';
}

// Close connection
$conn->close();

// Log the response before sending
error_log("Response: " . json_encode($response));

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
