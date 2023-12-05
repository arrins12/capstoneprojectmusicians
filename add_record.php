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
    $artist = sanitize_input($_POST["artist"]);
    $song = sanitize_input($_POST["song"]);
    $album = sanitize_input($_POST["album"]);
    $year = filter_var($_POST["year"], FILTER_VALIDATE_INT);
    $genre = sanitize_input($_POST["genre"]);

    // Check for valid year input
    if ($year === false || $year < 1900 || $year > date("Y")) {
        echo "Invalid year input";
    } else {
        // Insert record into Musicians table
        $stmt = $conn->prepare("INSERT INTO Musicians (artist, song, album, year, genre) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $artist, $song, $album, $year, $genre);

        if ($stmt->execute()) {
            echo "Record added successfully. <a href='menu.php'>Go to Home</a>";
            
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        // Close prepared statement
        $stmt->close();
    }
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
    <title>Add Record</title>
    <style>
       body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('music-menu.png'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
           /* Set text color to white for better visibility */
        }

        h1 {
            text-align: center;
            padding-top: 50px;
            color:blue; /* Adjust as needed */
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
        }

        input {
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
    <h1>Add Record</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="artist">Artist:</label>
        <input type="text" name="artist" required>

        <label for="song">Song:</label>
        <input type="text" name="song" required>

        <label for="album">Album:</label>
        <input type="text" name="album" required>

        <label for="year">Year:</label>
        <input type="number" name="year" required>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" required>

        <input type="submit" value="Add Record">
    </form>
    <p class="return-to-menu-container"><a class="return-to-menu" href="menu.php">Return to Main Menu</a></p>

</body>
</html>



