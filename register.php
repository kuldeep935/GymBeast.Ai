<?php
include 'db.php'; // database connection file

echo "<pre>";
print_r($_POST);
echo "</pre>";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: register.html?error=empty_fields");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?error=invalid_email");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: register.html?error=password_mismatch");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: register.html?error=password_length");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: register.html?error=email_exists");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $fullname;
        header("Location: main.html");
        exit();
    } else {
        header("Location: register.html?error=registration_failed");
        exit();
    }
} else {
    // If not POST request, redirect to registration page
    header("Location: register.html");
    exit();
}
?>
