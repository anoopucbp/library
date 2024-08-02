<?php
header('Content-Type: application/json');

// Establish database connection (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get search parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Construct SQL query
$sql = "SELECT * FROM books WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (Bname LIKE '%$search%' OR Author LIKE '%$search%')";
}

if (!empty($category)) {
    $sql .= " AND Category = '$category'";
}

$result = $conn->query($sql);

// Fetch results and output JSON
if ($result) {
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode($books);
} else {
    echo json_encode([]);
}

// Close connection
$conn->close();
?>
