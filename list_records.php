<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Database connection variables
$hn = "localhost";
$un = "userfa23";
$pw = "pwdfa23";
$db = "bcs350fa23";

// Create database connection
$conn = new mysqli($hn, $un, $pw, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve records from Musicians table
$sql = "SELECT * FROM Musicians";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Records</title>
       <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleMainMenuLink() {
            var link = document.getElementById('mainMenuLink');
            link.style.display = (link.style.display === 'none' || link.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>List Records</h1>
    <table>
        <tr>
            <th>Artist</th>
            <th>Song</th>
            <th>Album</th>
            <th>Year</th>
            <th>Genre</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["artist"] . "</td>";
                echo "<td>" . $row["song"] . "</td>";
                echo "<td>" . $row["album"] . "</td>";
                echo "<td>" . $row["year"] . "</td>";
                echo "<td>" . $row["genre"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        // Close database connection
       $conn->close();
       ?>
    </table>

    <p><a href="index.php">Return to Main Menu</a></p>
	
// Close database connection
$conn->close();
?>

</body>
</html>

