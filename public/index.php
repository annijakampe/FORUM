<?php
require '../database/db.php';

$stmt = $pdo->query("SELECT * FROM topics ORDER BY created_at DESC");
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($topics)) {
    $message = "No topics found.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="../assets/index.css">
</head>

<body>
    <div class="container">
        <h1>Forum</h1>


        <a href="../includes/new_topic.php">Create New Topic</a>

        <?php if (!empty($message)): ?>

            <p><?= htmlspecialchars($message) ?></p>
        <?php else: ?>

            <ul>
                <?php foreach ($topics as $topic): ?>
                    <li>
                        <a href="../includes/topic.php?id=<?= $topic['id'] ?>">
                            <?= htmlspecialchars($topic['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>