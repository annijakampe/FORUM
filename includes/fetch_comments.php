<?php
include '../database/db.php';

// iegūst comments un lietotājvārdu datus no db
if (isset($_GET['topic_id'])) {
    $topic_id = $_GET['topic_id'];

    try {

        $stmt = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.topic_id = ? ORDER BY comments.created_at ASC");
        $stmt->execute([$topic_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
