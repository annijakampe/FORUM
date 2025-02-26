<?php
session_start();
include '../database/db.php';
include '../includes/fetch_topics.php';

$is_guest = !isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forum</title>
    <link rel="stylesheet" href="../assets/index.css?t=123">
</head>
<!-- * ------------------- Sākums ----------------- *  -->

<body>
    <h1 style="font-size: 40px  ">HOME</h1>
    <?php if ($is_guest): ?>
        <p>Log in to post!</p>
        <p><a href="../includes/login.php">login</a> or <a href="../includes/register.php">register</a></p>
    <?php else: ?>
        <p style="font-size: 25px">Welcome!</p>
        <p>Logout here →<a href=" ../includes/logout.php">logout</a> </p>

    <?php endif; ?>
    <h1 style="font-size: 40px  ">FORUM</h1>

    <!-- * ------------------- TOPICS ----------------- *  -->
    <?php if (!$is_guest): ?>
        <h2>Create a New Topic </h2>
        <form method="post" action="../includes/post_topic.php">
            <input type="text" name="title" placeholder="Topic Title" required><br>
            <input type="submit" value="Post Topic">
        </form>
    <?php endif; ?>
    <?php
    try {
        // topics ar lietotāja vārdiem no db
        $stmt = $pdo->query("SELECT topics.*, users.username FROM topics JOIN users ON topics.user_id = users.id ORDER BY topics.created_at DESC");
        while ($topic = $stmt->fetch()) {
            echo "<div class='topic'>";
            echo "<h3>" . htmlspecialchars($topic['title']) . "</h3>";
            echo "<p>by " . htmlspecialchars($topic['username'])  . "</p>";

            // Pārbauda vai lietotājs ir topic autors un tad ir iespēja dzēst topic
            if (!$is_guest && $topic['user_id'] == $_SESSION['user_id']) {
                echo "<form method='post' action='../includes/delete_topic.php'>";
                echo "<input type='hidden' name='topic_id' value='" . $topic['id'] . "'>";
                echo "<input type='submit' value='Delete Topic'>";
                echo "</form><br>";
            }

            //  * ------------------- KOMENTĀRI ----------------- 
            // komentāri ar lietotāja vārdiem
            $stmt_comments = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.topic_id = ? ORDER BY comments.created_at ASC");
            $stmt_comments->execute([$topic['id']]);
            while ($comment = $stmt_comments->fetch()) {
                echo "<div class='comment'>";
                echo "<p>" . htmlspecialchars($comment['content']) . "</p>";
                echo "<p>by " . htmlspecialchars($comment['username']) . "</p>";
                if (!$is_guest && $comment['user_id'] == $_SESSION['user_id']) {
                    echo "<a href='../includes/delete_comment.php?id=" . $comment['id'] . "'>Delete</a>";
                }
                echo "</div>";
            }

            // Komentēšanas iespēja tiem kas ir ielogoti
            if (!$is_guest) {
                echo "<form method='post' action='../includes/post_comment.php'>";
                echo "<input type='hidden' name='topic_id' value='" . htmlspecialchars($topic['id']) . "'>";
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