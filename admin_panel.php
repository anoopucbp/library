<?php
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

// Update order if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $OrderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
    $Status = isset($_POST['Status']) ? $conn->real_escape_string($_POST['Status']) : '';
    $assigned_to = isset($_POST['assigned_to']) ? $conn->real_escape_string($_POST['assigned_to']) : '';
    $remarks = isset($_POST['remarks']) ? $conn->real_escape_string($_POST['remarks']) : '';
    $D_date = isset($_POST['Delivered_on']) ? $_POST['Delivered_on'] : ''; // Ensure to capture Delivered_on correctly

    if (!empty($OrderID) && !empty($Status)) {
        // Format the delivered date for MySQL (assuming it's in YYYY-MM-DD format)
        $formatted_D_date = date('Y-m-d', strtotime($D_date));
        
        $update_sql = "UPDATE orders SET Status='$Status', assigned_to='$assigned_to', remarks='$remarks', D_date='$formatted_D_date' WHERE OrderID='$OrderID'";
        
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('Order updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating order: " . $conn->error . "');</script>";
        }
    }
}

// Fetch orders data
$sql = "SELECT OrderID, Bno, Bname, Author, UserName, Mob_No, OrderDate, rtn_date, assigned_to, D_date, Status, remarks FROM orders";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === FALSE) {
    echo "Error executing query: " . $conn->error;
} else {
    // HTML code begins here
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel - Manage Orders</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #007bff;
                color: #fff;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            /* Color coding */
            .order-yellow {
                background-color: #fff3cd !important;
            }

            .order-red {
                background-color: #f8d7da !important;
            }

            .order-green {
                background-color: #d4edda !important;
            }

            .order-pending {
                background-color: #fff3cd !important;
            }

            .order-rejected {
                background-color: #f5c6cb !important;
            }

            .button {
                background-color: #007bff;
                color: white;
                padding: 10px;
                text-align: center;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .button:hover {
                background-color: #0056b3;
            }

            .form-container {
                max-width: 800px;
                margin: auto;
            }

            .update-form {
                display: none;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .update-form label {
                display: block;
                margin-bottom: 10px;
                font-weight: bold;
                color: #555;
            }

            .update-form input,
            .update-form select,
            .update-form textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-sizing: border-box;
                font-size: 16px;
            }

            .update-form input[type="submit"] {
                background-color: #007bff;
                color: #fff;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .update-form input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .readonly {
                background-color: #f4f4f4;
                cursor: not-allowed;
            }
        </style>
    </head>
    <body>
        <h2>Admin Panel - Manage Orders</h2>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Book Number</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>User Name</th>
                    <th>Mobile No</th>
                    <th>Order Date</th>
                    <th>Return Date</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Delivered on</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Color coding based on status and days since order
                        $rowClass = '';
                        $orderDate = strtotime($row['OrderDate']);
                        $status = $row['Status'];

                        // Calculate days since order
                        $daysSinceOrder = floor((time() - $orderDate) / (60 * 60 * 24));

                        if ($status == 'Delivered') {
                            $rowClass = 'order-green';
                        } elseif ($status == 'Rejected') {
                            $rowClass = 'order-rejected';
                        } elseif ($status == 'Pending') {
                            if ($daysSinceOrder > 2) {
                                $rowClass = 'order-red';
                            } elseif ($daysSinceOrder > 1) {
                                $rowClass = 'order-yellow';
                            } else {
                                $rowClass = 'order-pending';
                            }
                        }

                        echo "<tr class='$rowClass'>";
                        echo "<td>" . $row['OrderID'] . "</td>";
                        echo "<td>" . $row['Bno'] . "</td>";
                        echo "<td>" . $row['Bname'] . "</td>";
                        echo "<td>" . $row['Author'] . "</td>";
                        echo "<td>" . $row['UserName'] . "</td>";
                        echo "<td>" . $row['Mob_No'] . "</td>";
                        echo "<td>" . date('Y-m-d', $orderDate) . "</td>";
                        echo "<td>" . $row['rtn_date'] . "</td>";
                        echo "<td>" . $row['assigned_to'] . "</td>";
                        echo "<td>" . $row['Status'] . "</td>";
                        echo "<td>" . ($row['D_date'] != '0000-00-00' ? date('Y-m-d', strtotime($row['D_date'])) : '') . "</td>"; // Display delivered date if not '0000-00-00'
                        echo "<td>" . $row['remarks'] . "</td>";
                        echo "<td><button class='button' onclick='showUpdateForm(" . json_encode($row) . ")'>Edit</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Update form (initially hidden) -->
        <div class="update-form" id="updateForm">
            <h3>Update Order</h3>
            <form action="admin_panel.php" method="POST">
                <label for="OrderID">Order ID:</label>
                <input type="text" id="OrderID" name="OrderID" readonly class="readonly">

                <label for="Status">Status:</label>
                <select id="Status" name="Status" required>
                    <option value="Pending">Pending</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Rejected">Rejected</option>
                </select>

                <label for="Delivered_on">Delivered on:</label>
                <input type="text" id="Delivered_on" name="Delivered_on" placeholder="Enter Delivery Date">

                <label for="assigned_to">Assigned To:</label>
                <input type="text" id="assigned_to" name="assigned_to" placeholder="Enter assigned person">

                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" placeholder="Enter remarks" rows="4"></textarea>

                <input type="submit" value="Update Order">
            </form>
        </div>

        <script>
            function showUpdateForm(order) {
                // Display the form
                document.getElementById('updateForm').style.display = 'block';
                
                // Populate the form with the order details
                document.getElementById('OrderID').value = order.OrderID;
                document.getElementById('Status').value = order.Status;
                document.getElementById('assigned_to').value = order.assigned_to;
                document.getElementById('remarks').value = order.remarks;
                document.getElementById('Delivered_on').value = order.D_date; // Populate delivered date field
                
                // Scroll to the form
                document.getElementById('updateForm').scrollIntoView();
            }
        </script>
    </body>
    </html>

    <?php
}
$conn->close();
?>
