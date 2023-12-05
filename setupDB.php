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

// Create Musicians table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Musicians (
    MusicianID INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    artist VARCHAR(128) NULL,
    song VARCHAR(128) NULL,
    album VARCHAR(128) NULL,
    year SMALLINT(6) NULL,
    genre VARCHAR(128) NULL,
    INDEX(artist(20)),
    INDEX(song(20)),
    INDEX(album(20)),
    INDEX(year),
    INDEX(genre(20))
  ) ENGINE=InnoDB";
  

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Table Musicians created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert initial values into the Musicians table
$sql = "INSERT INTO Musicians (artist, song, album, year, genre)
VALUES 
  ('J.Cole', 'Forbidden Fruit', 'Born SInner', 2013, 'Hip-Hop/Rap'),
  ('Brent Faiyaz', 'Dead Man Walking', 'Wasteland', 2013, 'R&B'),
  ('Erykah Badu', 'Window Seats', 'New Amerykah Par', 2013, 'NeoSoul'),
  ('Michael Jackson', 'Billie Jeans', 'Thriller', 1982, 'R&B'),
  ('Beres Hammond', 'Tempted to Touch', 'A Love Affair', 1991, 'Reggae'),
  ('Machel Montana', 'Happiest Man Alive', 'Happiest Man Alive', 2014, 'Soca & Dancehall'),
  ('Louis Armstrong', 'Wild Man Blues', 'Highlights From his Decca Years', 1994, 'Jazz')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Initial values inserted into Musicians table successfully<br>";
} else {
    echo "Error inserting values into Musicians table: " . $conn->error . "<br>";
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users( 
  username VARCHAR(60) PRIMARY KEY,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(60) NOT NULL
)";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert initial values into the users table
$sql = "INSERT INTO users (username, email, password)
VALUES
  ('Jerome Bond', 'JBond@example.com', 'HeyyWo193'),
  ('James Smith', 'Jsmith@example.com', 'Jsmith345'),
  ('Lebron James', 'Ljames@example.com', 'NBAgames423')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Initial values inserted into users table successfully<br>";
} else {
    echo "Error inserting values into users table: " . $conn->error . "<br>";
}

// Close database connection
$conn->close();
?>
