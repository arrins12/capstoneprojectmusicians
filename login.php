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

// Initialize the error variable
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input
    $username = sanitize_input($username);
    $password = sanitize_input($password);

    // Check if the entered username and password match a record in the database
    $checkUserQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password using password_verify
        if (password_verify($user['salt'] . $password, $user['password'])) {
            // Password is correct, start a new session
            session_start();
            $_SESSION["username"] = $username;
            // Redirect the user to the main menu page
            header("Location: menu.php");
            exit();
        } else {
            // Incorrect password
            $error = "Incorrect username or password. Please try again.";
        }
    } else {
        // User not found
        $error = "Incorrect username or password. Please try again.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();

function sanitize_input($input) {
    // Use appropriate methods to sanitize the input (e.g., mysqli_real_escape_string, htmlspecialchars, etc.)
    return htmlspecialchars($input);
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image:url(music-menu.png);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 16px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 0; /* Add margin at the top */
        }
    </style>
    <script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            if (username === "" || password === "") {
                alert("Username and password are required.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <h2>User Login</h2>
        <?php if (!empty($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <input type="submit" value="Log In">
        </div>
    </form>
</body>
</html>
