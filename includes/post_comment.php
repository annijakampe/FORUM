<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

require '../database/db.php';

$topic_id = $_POST['topic_id'];
$content = $_POST['content'];
$user_id = $_SESSION['user_id'];

// ievada komentāru datu bāzē
$stmt = $pdo->prepare('INSERT INTO comments (topic_id, content, user_id) VALUES (?, ?, ?)');
$stmt->execute([$topic_id, $content, $user_id]);


header("Location: ../public/index.php?topic_id=" . $topic_id);
exit();
