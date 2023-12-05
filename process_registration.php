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
    // Collect and sanitize form data
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
}

// Close the database connection
$conn->close();

function sanitize_input($input) {
    // Use appropriate methods to sanitize the input (e.g., mysqli_real_escape_string, htmlspecialchars, etc.)
    return htmlspecialchars($input);
}

function validateRegistrationInput($email, $username, $password, $confirmPassword) {
    $errorMessage = "";

    // Add your existing validation checks here
    $errorMessage .= validateEmail($email);
    // Add other validation functions if needed
    // $errorMessage .= validateUsername($username);
    // $errorMessage .= validatePassword($password);
    // $errorMessage .= validateConfirmPassword($password, $confirmPassword);

    return $errorMessage;
}

function validateEmail($email) {
    // Basic email validation using filter_var
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Additional validation logic if needed

    return ""; // Return an empty string if the email is valid
}
?>
