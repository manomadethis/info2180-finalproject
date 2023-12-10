<?php

session_start();
ob_start(); // Start output buffering

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "";
$dbname = "schema";

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

if (!isset($_POST['titles'])) {
    $stmt = $conn->prepare("SELECT id, firstname, lastname FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $row) {
        $name = $row['firstname'] . " " . $row['lastname'];
        $vname = $row['firstname'] . "_" . $row['lastname'];
        $selected = $row['id'] == 1 ? 'selected = "selected"' : '';
        echo "<option value='{$vname}' {$selected}>{$name}</option>";
    }
} else {
    if (!isset($_POST['assigned'], $_SESSION["user"])) {
        // Handle the error
        echo "Error: 'assigned' field or session user is not set";
        exit;
    }
    $title = htmlspecialchars($_POST['titles']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = filter_var(htmlspecialchars($_POST['emailaddr']), FILTER_SANITIZE_EMAIL);
    $tele = filter_var(htmlspecialchars($_POST['tele']), FILTER_SANITIZE_NUMBER_INT);
    $company = htmlspecialchars($_POST['company']);
    $type = htmlspecialchars($_POST['types']);
    $assigned = htmlspecialchars($_POST['assigned']);
    $creator_id = $_SESSION["user"];
    $created = date("Y-m-d h:i:sa");
    $updated = $created;

    $assigneds = explode(" ", $assigned);
    if (count($assigneds) != 2) {
        echo "Error: 'assigned' field is not in the correct format";
        exit;
    }
    $assigneds[0] = trim($assigneds[0]);
    $assigneds[1] = trim($assigneds[1]);

    $stmt = $conn->prepare("SELECT id FROM users WHERE firstname LIKE :assignedf AND lastname LIKE :assignedl");
    $stmt->bindParam(":assignedf", $assigneds[0]);
    $stmt->bindParam(":assignedl", $assigneds[1]);
    $stmt->execute();
    $assigned_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($assigned_id)) {
        echo "Error: No user found with the name {$assigneds[0]} {$assigneds[1]}";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contacts (created_by, title, firstname, lastname, email, telephone, company, `type`, assigned_to, created_at, updated_at) VALUES (:creator, :title, :firstname, :lastname, :email, :tele, :company, :tyype, :assigned, :created, :updated)");
    $stmt->bindParam(":creator", $creator_id);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":firstname", $firstname);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":tele", $tele);
    $stmt->bindParam(":company", $company);
    $stmt->bindParam(":tyype", $type);
    $stmt->bindParam(":assigned", $assigned_id[0]['id']);
    $stmt->bindParam(":created", $created);
    $stmt->bindParam(":updated", $updated);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: Could not create contact";
    }
}

ob_end_flush(); // End output buffering
?>