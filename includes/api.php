<?php
require '../database/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to check if the user is logged in
session_start();

$action = $_GET['action'] ?? '';

// New Topic - Handle POST Request
if ($action == 'new_topic' && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: Unauthorized access.");
    }

    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Invalid CSRF token.");
    }

    $title = trim($_POST['title']);

    // Basic validation
    if (empty($title)) {
        die("Error: Title cannot be empty.");
    }

    if (strlen($title) > 255) {
        die("Error: Title is too long (max 255 characters).");
    }

    // Sanitize the title to prevent XSS
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    try {
        // Insert new topic into the database
        $stmt = $pdo->prepare("INSERT INTO topics (title, user_id) VALUES (?, ?)");
        $stmt->execute([$title, $_SESSION['user_id']]);

        // Redirect back to the forum index page
        header("Location: ../public/index.php");
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Error: Could not create topic.");
    }
}

// Fetch Topics
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

// Invalid action
die("Error: Invalid action.");
