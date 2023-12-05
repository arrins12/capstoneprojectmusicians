<?php
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $artist = sanitize_input($_POST["artist"]);

    // Check if the record exists
    $selectStmt = $conn->prepare("SELECT * FROM Musicians WHERE artist = ?");
    $selectStmt->bind_param("s", $artist);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the record
        $deleteStmt = $conn->prepare("DELETE FROM Musicians WHERE artist = ?");
        $deleteStmt->bind_param("s", $artist);
        $deleteStmt->execute();

        echo "Record with Artist '$artist' deleted successfully.";
        echo "<a href='menu.php'>Go to Home</a>";
    } else {
        echo "Record with Artist '$artist' not found.";
    }

    // Close prepared statements
    $selectStmt->close();
    $deleteStmt->close();
}

// Function to sanitize user input
function sanitize_input($input) {
    // Sanitize the input using appropriate methods (e.g., mysqli_real_escape_string, htmlspecialchars, etc.)
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(music-menu2.png);
        }

        h1 {
            text-align: center;
            color: #dc3545;
            margin-top: 200px;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c82333;
        }

    a.return-to-menu {
    display: inline-block;
    padding: 10px 20px;
    background-color: #dc3545;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

a.return-to-menu:hover {
    background-color: #c82333;
}

/* Center the link */
p.return-to-menu-container {
    text-align: center;
}
    </style>
</head>
<body>
    <h1>Delete Record</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="artist">Artist:</label>
        <input type="text" name="artist" required><br>

        <input type="submit" value="Delete">
    </form>
    <p class="return-to-menu-container"><a class="return-to-menu" href="menu.php">Return to Main Menu</a></p>
</body>
</html>
