<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $bind = $stmt->bind_param("sss", $username, $password, $email);
    if ($bind === false) {
        die("Bind failed: " . htmlspecialchars($stmt->error));
    }

    $execute = $stmt->execute();
    if ($execute) {
        echo "Registration successful!";
    } else {
        echo "Execute failed: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}
?>

<form method="post" action="../includes/register.php">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="email" name="email" required><br>
    <input type="submit" value="Register">
</form>

<br>
<a href="../public/index.php"><button>Back to Forum</button></a>