<?php
session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $topic_id = $_POST['topic_id'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, topic_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $topic_id, $content);

    if ($stmt->execute()) {
        echo "Comment posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../public/index.php");
