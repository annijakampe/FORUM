<?php
include '../database/db.php';

// iegūst tēmas un lietotājvārdu datus no db
try {
    $stmt = $pdo->prepare("SELECT topics.*, users.username FROM topics JOIN users ON topics.user_id = users.id ORDER BY topics.created_at DESC");
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
