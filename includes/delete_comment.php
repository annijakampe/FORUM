<?php
session_start();
include '../database/db.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Dzēš komentāru, kas pieder ielogotajam lietotājam
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);


        if ($stmt->rowCount() > 0) {
            echo "Comment deleted successfully!";
        } else {
            echo "No comment found or you're not authorized to delete this comment.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

header("Location: ../public/index.php");
exit();
