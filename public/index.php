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
        <p>You are viewing as a guest. Please <a href="../includes/login.php">login</a> or <a href="../includes/register.php">register</a> to post comments or topics.</p>
    <?php else: ?>
        <p>Welcome, user! You can <a href="../includes/logout.php">logout</a> here.</p>
    <?php endif; ?>

    <h2>Topics</h2>
    <?php
    $result = $conn->query("SELECT * FROM topics");
    while ($topic = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $topic['title'] . "</h3>";
        echo "<p>" . $topic['content'] . "</p>";

        // Fetch comments for each topic
        $stmt = $conn->prepare("SELECT * FROM comments WHERE topic_id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $bind = $stmt->bind_param("i", $topic['id']);
        if ($bind === false) {
            die("Bind failed: " . htmlspecialchars($stmt->error));
        }

        $execute = $stmt->execute();
        if ($execute === false) {
            die("Execute failed: " . htmlspecialchars($stmt->error));
        }

        $comments = $stmt->get_result();
        while ($comment = $comments->fetch_assoc()) {
            echo "<div>";
            echo "<p>" . $comment['content'] . "</p>";
            if (!$is_guest && $comment['user_id'] == $_SESSION['user_id']) {
                echo "<a href='../includes/delete_comment.php?id=" . $comment['id'] . "'>Delete</a>";
            }
            echo "</div>";
        }
        $stmt->close();

        if (!$is_guest) {
            echo "<form method='post' action='../includes/post_comment.php'>";
            echo "<input type='hidden' name='topic_id' value='" . $topic['id'] . "'>";
            echo "<textarea name='content'></textarea><br>";
            echo "<input type='submit' value='Post Comment'>";
            echo "</form>";
        }

        echo "</div>";
    }
    $conn->close();
    ?>
</body>

</html>