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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST["email"]);
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);
    $confirmPassword = sanitize_input($_POST["confirmPassword"]);

    // Validate input
    $errorMessage = validateRegistrationInput($email, $username, $password, $confirmPassword);

    if ($errorMessage !== "") {
        echo "<div class='error'>$errorMessage</div>";
    } else {
        // Check if the username is available
        $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($checkUsernameQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Username is not available
            echo "<div class='error'>Sorry, the username '$username' is already taken. Please choose another username.</div>";
        } else {
            // Username is available, proceed with registration
            // Generate a random salt
            $salt = bin2hex(random_bytes(16));
            // Concatenate the salt with the password before hashing
            $saltedPassword = $salt . $password;
            // Hash the salted password
            $hashedPassword = password_hash($saltedPassword, PASSWORD_DEFAULT);

            // Insert user into the users table using a prepared statement
            $insertUserQuery = "INSERT INTO users (username, email, password, salt) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertUserQuery);
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $salt);

            if ($stmt->execute()) {
                echo "Registration successful! Welcome, $username!";
                // You can redirect the user to a login page or any other page
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}

function sanitize_input($input) {
    // Use appropriate methods to sanitize the input (e.g., mysqli_real_escape_string, htmlspecialchars, etc.)
    return htmlspecialchars($input);
	
	function validateRegistrationInput($email, $username, $password, $confirmPassword) {
    $errorMessage = "";

    // Add your existing validation checks here
    $errorMessage .= validateEmail($email);
    $errorMessage .= validateUsername($username);
    $errorMessage .= validatePassword($password);
    $errorMessage .= validateConfirmPassword($password, $confirmPassword);

    return $errorMessage;
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url(music-menu2.png);
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
    </style>
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var errorDiv = document.getElementById("passwordError");

            if (password !== confirmPassword) {
                errorDiv.innerHTML = "Passwords do not match.";
                return false;
            } else {
                errorDiv.innerHTML = "";
                return true;
            }
        }
    </script>
</head>
<body>
    <form action="process_registration.php" method="post" onsubmit="return validatePassword()">
        <h2>User Registration</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" name="confirmPassword" id="confirmPassword" required>
        <span class="error" id="passwordError"></span>

        <input type="submit" value="Register">
    </form>
</body>
</html>
