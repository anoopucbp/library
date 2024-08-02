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

// Handle form submission for adding or updating a member
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memberID = isset($_POST['MemberID']) ? intval($_POST['MemberID']) : 0;
    $Name = $conn->real_escape_string($_POST['Name']);
    $Email = $conn->real_escape_string($_POST['Email']);
    $Phone = $conn->real_escape_string($_POST['Phone']);
    $Address = $conn->real_escape_string($_POST['Address']);
    $JoinDate = $conn->real_escape_string($_POST['JoinDate']);

    if ($memberID > 0) {
        // Update member
        $sql = "UPDATE memberships SET Name='$Name', Email='$Email', Phone='$Phone', Address='$Address', JoinDate='$JoinDate' WHERE MemberID='$memberID'";
    } else {
        // Insert new member
        $sql = "INSERT INTO memberships (Name, Email, Phone, Address, JoinDate) VALUES ('$Name', '$Email', '$Phone', '$Address', '$JoinDate')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record saved successfully.'); window.location.href='membership_console.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='membership_console.php';</script>";
    }
