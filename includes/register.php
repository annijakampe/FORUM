<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    try {

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");


        $stmt->execute([$username, $password, $email]);

        echo "Registration successful!</br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<link rel="stylesheet" href="../assets/log_reg.css?t=123">
<form method="post" action="../includes/register.php">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="email" name="email" required><br>
    <input type="submit" value="Register">
</form>

<br>
<a href="../public/index.php"><button>Back to Forum</button></a>