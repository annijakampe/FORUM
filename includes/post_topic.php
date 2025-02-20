<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../database/db.php'; // Ensure this includes the correct PDO connection

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to post a topic.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $user_id = $_SESSION['user_id'];

    if (empty($title)) {
        die("Title cannot be empty.");
    }

    // Use $pdo for database operations
    try {
        $stmt = $pdo->prepare("INSERT INTO topics (user_id, title) VALUES (?, ?)");
        $stmt->execute([$user_id, $title]);

        header("Location: ../public/index.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
