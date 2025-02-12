<?php
$api_url = "http://localhost/api.php?action=fetch_topics";

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$topics = json_decode($response, true);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Fetched Topics</title>
</head>

<body>
    <h1>Fetched Topics from API</h1>
    <ul>
        <?php foreach ($topics as $topic): ?>
            <li><?= htmlspecialchars($topic['title']) ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>