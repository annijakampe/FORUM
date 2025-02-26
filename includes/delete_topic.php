<?php
session_start();
require '../database/db.php';


if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate topic_id input to ensure it's a valid integer
    if (isset($_POST['topic_id']) && filter_var($_POST['topic_id'], FILTER_VALIDATE_INT)) {
        $topic_id = $_POST['topic_id'];
    } else {
        die("Invalid topic ID.");
    }

    // Fetch the topic to check ownership
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM topics WHERE id = ?");
        $stmt->execute([$topic_id]);
        $topic = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if topic exists and user is the owner
        if (!$topic || $topic['user_id'] !== $_SESSION['user_id']) {
            die("Unauthorized action.");
        }

        // Begin a transaction
        $pdo->beginTransaction();

        // Delete replies first (to avoid foreign key issues)
        $deleteReplies = $pdo->prepare("DELETE FROM comments WHERE topic_id = ?");
        $deleteReplies->execute([$topic_id]);

        // Delete the topic
        $deleteTopic = $pdo->prepare("DELETE FROM topics WHERE id = ?");
        $deleteTopic->execute([$topic_id]);

        // Commit the transaction
        $pdo->commit();

        // Redirect back to forum index
        header("Location: ../public/index.php");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $pdo->rollBack();
        die("An error occurred while deleting the topic: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
