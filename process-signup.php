<?php

$mysqli = require __DIR__ . "/db.php";

$sql = "INSERT INTO user (username, email, number, password)
        VALUES (?, ?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Validate and sanitize user inputs
$username = $_POST["username"];
$email = $_POST["email"];
$number = $_POST["number"];
$password = $_POST["password"];

if (empty($username) || empty($email) || empty($password)) {
    die("Please fill in all required fields.");
}

// You may add additional validation for email and number if necessary

// Sanitize inputs
$username = filter_var($username, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$number = filter_var($number, FILTER_SANITIZE_STRING);

// Hash the password
$password = password_hash($password, PASSWORD_DEFAULT);

$stmt->bind_param("ssss", $username, $email, $number, $password);

if ($stmt->execute()) {
    header("Location: Login.php");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Email already taken.");
    } else {
        die("Error: " . $mysqli->error);
    }
}
