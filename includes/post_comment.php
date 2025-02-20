<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $topic_id = $_POST['topic_id'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, topic_id, content) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $bind = $stmt->bind_param("iis", $user_id, $topic_id, $content);
    if ($bind === false) {
        die("Bind failed: " . htmlspecialchars($stmt->error));
    }

    $execute = $stmt->execute();
    if ($execute) {
        echo "Comment posted successfully!";
    } else {
        echo "Execute failed: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

header("Location: index.php");
