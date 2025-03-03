<?php
session_start();
require '../database/db.php';

//Pārbauda vai lietotājs ir ielogojies
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

// Pārbauda, vai pieprasījums ir ar POST metodi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //  Pārbauda, vai tēmai ir derīgs ID
    if (isset($_POST['topic_id']) && filter_var($_POST['topic_id'], FILTER_VALIDATE_INT)) {
        $topic_id = $_POST['topic_id'];
    } else {
        die("Invalid topic ID.");
    }

    // Pārbauda, vai lietotājs ir tēmas īpašnieks
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM topics WHERE id = ?");
        $stmt->execute([$topic_id]);
        $topic = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$topic || $topic['user_id'] !== $_SESSION['user_id']) {
            die("Unauthorized action.");
        }

        // Sāk transakciju, lai nodrošinātu drošu dzēšanu
        $pdo->beginTransaction();

        // Izdzēš visus komentārus, kas saistīti ar tēmu
        $deleteReplies = $pdo->prepare("DELETE FROM comments WHERE topic_id = ?");
        $deleteReplies->execute([$topic_id]);

        // Izdzēš pašu tēmu
        $deleteTopic = $pdo->prepare("DELETE FROM topics WHERE id = ?");
        $deleteTopic->execute([$topic_id]);

        // Apstiprina izmaiņas
        $pdo->commit();


        header("Location: ../public/index.php");
        exit;
    } catch (Exception $e) {

        $pdo->rollBack();
        die("An error occurred while deleting the topic: " . $e->getMessage());
    }
} else {
    die("Invalid request method."); // Ja pieprasījums nav ar POST, pārtrauc skriptu
}
