<?php
include 'db.php';

// Test database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br>";
}

// Test if users table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "Users table exists!<br>";
} else {
    echo "Users table does not exist. Please create it using the SQL query provided earlier.<br>";
}

// Show current tables in database
echo "<br>Current tables in database:<br>";
$tables = $conn->query("SHOW TABLES");
while ($table = $tables->fetch_array()) {
    echo $table[0] . "<br>";
}

$conn->close();
?> 