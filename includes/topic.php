<?php
require '../database/db.php';

$topic_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE topic_id = ? ORDER BY created_at ASC");
$stmt->execute([$topic_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($topic['title']) ?></title>
    <link rel="stylesheet" href="../assets/topic.css">
</head>

<body>
    <h1><?= htmlspecialchars($topic['title']) ?></h1>
    <h2>Replies:</h2>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li><?= htmlspecialchars($post['content']) ?> (<?= $post['created_at'] ?>)</li>
        <?php endforeach; ?>
    </ul>
    <h3>Reply to this topic</h3>
    <form action="../includes/post_reply.php" method="POST">
        <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
        <textarea name="content" required></textarea><br>
        <button type="submit">Post Reply</button>
        <!-- "Back to Home" button -->
        <a href="../public/index.php" class="btn btn-primary">Back to Home</a>

    </form>
</body>

</html>