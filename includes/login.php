<?php
session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $bind = $stmt->bind_param("s", $username);
    if ($bind === false) {
        die("Bind failed: " . htmlspecialchars($stmt->error));
    }

    $execute = $stmt->execute();
    if ($execute === false) {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    }

    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: ../public/index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}
?>

<form method="post" action="../includes/login.php">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

<br>
<a href="../public/index.php"><button>Back to Forum</button></a>