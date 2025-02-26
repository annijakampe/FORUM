<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require '../database/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../public/index.php");
    exit;
}
include '../includes/fetch_comments.php';
if (isset($_GET['id'])) {
    $topic_id = $_GET['id'];
    include '../includes/fetch_topic.php'; // Fetch single topic details
}

$topic_id = $_GET['id'];

// Fetch topic details
$stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$topic) {
    echo "Topic not found.";
    exit;
}

// Fetch replies
$stmt = $pdo->prepare("SELECT * FROM replies WHERE topic_id = ? ORDER BY created_at ASC");
$stmt->execute([$topic_id]);
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($topic['title']) ?></title>
    <link rel="stylesheet" href="../assets/topic.css"> <!-- Assuming you have a styles.css -->
</head>

<body>
    <div class="container">
        <h1><?= htmlspecialchars($topic['title']) ?></h1>


        <!-- Back to Forum Link -->
        <a href="../public/index.php" class="btn">Back to Forum</a>

        <h2>Replies</h2>
        <ul>
            <?php foreach ($replies as $reply): ?>
                <li>
                    <p><?= htmlspecialchars($reply['content']) ?></p>
                    <small>Posted by: <?= htmlspecialchars($reply['user_id']) ?> on <?= $reply['created_at'] ?></small>
                    <?php if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $reply['user_id']): ?>
                        <!-- Delete button for reply owner -->
                        <form action="../includes/delete_reply.php" method="POST" style="display:inline;">
                            <input type="hidden" name="reply_id" value="<?= $reply['id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this reply?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Reply form (visible only if logged in) -->
        <?php if (!empty($_SESSION['user_id'])): ?>
            <form action="../includes/post_reply.php" method="POST">
                <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
                <textarea name="content" required placeholder="Write your reply here..."></textarea>
                <button type="submit">Reply</button>
            </form>
        <?php else: ?>
            <p><a href="../includes/login.php">Login</a> to reply.</p>
        <?php endif; ?>
    </div>
</body>

</html>