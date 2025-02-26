<?php
include '../database/db.php';

try {
    // Fetch topics with user names
    $stmt = $pdo->prepare("SELECT topics.*, users.username FROM topics JOIN users ON topics.user_id = users.id ORDER BY topics.created_at DESC");
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Return topics as JSON (if needed) or prepare for rendering in HTML
// echo json_encode($topics);
