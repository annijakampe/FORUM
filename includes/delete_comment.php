<?php
session_start();
include '../database/db.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $comment_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $bind = $stmt->bind_param("ii", $comment_id, $user_id);
    if ($bind === false) {
        die("Bind failed: " . htmlspecialchars($stmt->error));
    }

    $execute = $stmt->execute();
    if ($execute) {
        echo "Comment deleted successfully!";
    } else {
        echo "Execute failed: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

header("Location: ../public/index.php");
