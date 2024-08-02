<?php
// Path to the CSV file
$csvFile = 'C:\Users\Administrator\Downloads\book3.txt';

// Open the CSV file for reading
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Skip the first row (header)
    fgetcsv($handle);

    // Loop through the rows of the CSV file
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Construct the SQL query
        $bno = $data[0];
        $bname = addslashes($data[1]);
        $author = addslashes($data[2]);
        $category = addslashes($data[3]);
        $publisher = addslashes($data[4]);
        $price = $data[5];
        $rack = addslashes($data[6]);
        $stats = addslashes($data[7]);

        // Generate the SQL INSERT statement
        $sql = "INSERT INTO books (Bno, Bname, Author, Category, Publisher, Price, Rack, Stats) VALUES ($bno, '$bname', '$author', '$category', '$publisher', '$price', '$rack', '$stats');";

        // Print the SQL query (you can also write it to a file or execute it directly)
        echo $sql . "<br>";
    }

    // Close the file handle
    fclose($handle);
} else {
    echo "Error opening the file.";
}
?>
