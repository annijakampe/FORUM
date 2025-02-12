<?php
require '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic_id = $_POST['topic_id'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO posts (topic_id, content) VALUES (?, ?)");
    $stmt->execute([$topic_id, $content]);
}

header("Location: ../includes/topic.php?id=$topic_id");
exit();
