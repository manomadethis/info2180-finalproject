<?php
session_start();

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "schema";

// Create a new PDO instance
$conn = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password);

// Sanitize and validate form inputs
$firstName = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lastName = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST['emailaddr'], FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];
$role = filter_var($_POST['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Set the default timezone
date_default_timezone_set(date_default_timezone_get());

try {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, password, email, role, created_at)
                            VALUES (:fname, :lname, :password, :email, :role, :date)");

    // Bind the form inputs to the SQL statement
    $stmt->bindValue(':fname', $firstName, PDO::PARAM_STR);
    $stmt->bindValue(':lname', $lastName, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->bindValue(':role', $role, PDO::PARAM_STR);
    $stmt->bindValue(':date', date('Y-m-d H:i:s'));

    // Execute the SQL statement
    $stmt->execute();

    // Redirect to the users page
    header('Location: index.php?tab=users');
    exit();
} catch (Exception $e) {
    // Handle any exceptions
    echo "An Exception has occurred: " . $e;
}