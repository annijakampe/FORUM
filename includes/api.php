<?php
require '../database/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_GET['action'] ?? '';

if ($action == 'new_topic' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);

    if (empty($title)) {
        die("Error: Title cannot be empty.");
    }

    if (strlen($title) > 255) {
        die("Error: Title is too long (max 255 characters).");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO topics (title) VALUES (?)");
        $stmt->execute([$title]);

        header("Location: ../public/index.php");
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Error: Could not create topic.");
    }
}

if ($action == 'fetch_topics') {
    try {
        $stmt = $pdo->query("SELECT * FROM topics ORDER BY created_at DESC");
        $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($topics);
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Error: Could not fetch topics.");
    }
}

die("Error: Invalid action.");
