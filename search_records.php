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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $field = sanitize_input($_POST["field"]);
    $value = sanitize_input($_POST["value"]);

    // Search for records in Musicians table
    $stmt = $conn->prepare("SELECT * FROM Musicians WHERE $field LIKE ?");
    $searchValue = "%" . $value . "%";
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Search Results</h2>";
        echo "<table>";
        echo "<tr><th>Artist</th><th>Song</th><th>Album</th><th>Year</th><th>Genre</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["artist"] . "</td>";
            echo "<td>" . $row["song"] . "</td>";
            echo "<td>" . $row["album"] . "</td>";
            echo "<td>" . $row["year"] . "</td>";
            echo "<td>" . $row["genre"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No records found.";
    }

    // Close prepared statement
    $stmt->close();
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
    <title>Search Records</title>
    <style>
         body {
            background-image: url('music-menu.png'); /* Replace 'your_image_path.jpg' with the path to your image */
            background-size: cover;
            background-position: center;
            margin: 0; /* Remove default margin */
            font-family: Arial, sans-serif; /* Optional: Set your preferred font-family */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #f5f5f5;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        h2 {
            margin-top: 20px;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            background-color: floralwhite;
        }

        select, input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a.return-to-menu {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

a.return-to-menu:hover {
    background-color: #0056b3;
}

/* Center the link */
p.return-to-menu-container {
    text-align: center;
}
    </style>
</head>
<body>
    <h1>Search Records</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="field">Search Field:</label>
        <select name="field">
            <option value="artist">Artist</option>
            <option value="song">Song</option>
            <option value="album">Album</option>
            <option value="year">Year</option>
            <option value="genre">Genre</option>
        </select><br>

        <label for="value">Search Value:</label>
        <input type="text" name="value" required><br>

        <input type="submit" value="Search">
    </form>
    <p class="return-to-menu-container"><a class="return-to-menu" href="menu.php">Return to Main Menu</a></p>

</body>
</html>
