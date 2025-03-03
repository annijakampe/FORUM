<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $email = trim($_POST['email']);

    try {
        // Pārbauda, vai username jau eksistē
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $usernameExists = $stmt->fetchColumn();

        // pārbauda, vai email jau eksistē
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($usernameExists) {
            echo "Username already taken. Please choose a different username.</br>";
        } elseif ($emailExists) {
            echo "Email already registered. Please use a different email.</br>";
        } else {
            // Ievieto jauno lietotāju datus datubāzē
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, $email]);

            echo "Registration successful!</br>";
        }
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