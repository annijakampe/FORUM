<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

require '../database/db.php';

$topic_id = $_POST['topic_id'];
$content = $_POST['content'];
$user_id = $_SESSION['user_id'];

// Insert the new comment into the database
$stmt = $pdo->prepare('INSERT INTO comments (topic_id, content, user_id) VALUES (?, ?, ?)');
$stmt->execute([$topic_id, $content, $user_id]);

// Redirect back to the forum page after inserting the comment
header("Location: ../public/index.php?topic_id=" . $topic_id);
exit();
