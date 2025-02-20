<?php
session_start();
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic_id']) && isset($_SESSION['user_id'])) {
    $topic_id = $_POST['topic_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Prepare the delete statement to delete the topic only if it belongs to the logged-in user
        $stmt = $pdo->prepare("DELETE FROM topics WHERE id = ? AND user_id = ?");
        $stmt->execute([$topic_id, $user_id]);

        // Check if any rows were affected (meaning a topic was deleted)
        if ($stmt->rowCount() > 0) {
            echo "Topic deleted successfully!";
        } else {
            echo "No topic found or you're not authorized to delete this topic.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

header("Location: ../public/index.php");
exit();
