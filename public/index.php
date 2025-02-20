<?php
session_start();
include '../database/db.php';

$is_guest = !isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forum</title>
</head>

<body>
    <h1>Welcome to the Forum</h1>
    <?php if ($is_guest): ?>
        <p>You are viewing as a guest. Please <a href="../includes/login.php">login</a> or <a href="../includes/register.php">register</a> to post topics or comments.</p>
    <?php else: ?>
        <p>Welcome, user! You can <a href="../includes/logout.php">logout</a> here.</p>
    <?php endif; ?>

    <!-- Form to create a new topic -->
    <?php if (!$is_guest): ?>
        <h2>Create a New Topic</h2>
        <form method="post" action="../includes/post_topic.php">
            <input type="text" name="title" placeholder="Topic Title" required><br>
            <input type="submit" value="Post Topic">
        </form>
    <?php endif; ?>

    <h2>Topics</h2>
    <?php
    try {
        // Fetch topics from the database using PDO
        $stmt = $pdo->query("SELECT * FROM topics ORDER BY created_at DESC");

        while ($topic = $stmt->fetch()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($topic['title']) . "</h3>";

            // Check if the logged-in user is the creator of the topic
            if (!$is_guest && $topic['user_id'] == $_SESSION['user_id']) {
                // Form to delete the topic
                echo "<form method='post' action='../includes/delete_topic.php'>";
                echo "<input type='hidden' name='topic_id' value='" . $topic['id'] . "'>";
                echo "<input type='submit' value='Delete Topic'>";
                echo "</form><br>";
            }

            // Fetch comments for each topic
            $stmt_comments = $pdo->prepare("SELECT * FROM comments WHERE topic_id = ?");
            $stmt_comments->execute([$topic['id']]);
            while ($comment = $stmt_comments->fetch()) {
                echo "<div>";
                echo "<p>" . htmlspecialchars($comment['content']) . "</p>";
                if (!$is_guest && $comment['user_id'] == $_SESSION['user_id']) {
                    echo "<a href='../includes/delete_comment.php?id=" . $comment['id'] . "'>Delete</a>";
                }
                echo "</div>";
            }

            // Comment form for logged-in users
            if (!$is_guest) {
                echo "<form method='post' action='../includes/post_comment.php'>";
                echo "<input type='hidden' name='topic_id' value='" . $topic['id'] . "'>";
                echo "<textarea name='content' placeholder='Write a comment...' required></textarea><br>";
                echo "<input type='submit' value='Post Comment'>";
                echo "</form>";
            }

            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
</body>

</html>