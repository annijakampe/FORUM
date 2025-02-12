<!DOCTYPE html>
<html>

<head>
    <title>Create Topic</title>
    <link rel="stylesheet" href="../assets/AddTopic.css">
</head>

<body>
    <h1>Create new Topic</h1>
    <form action="../includes/api.php?action=new_topic" method="POST">
        <input type="text" name="title" required placeholder="Enter topic title"><br>
        <button type="submit">Create Topic</button>
        <a href="../public/index.php" class="btn btn-primary">Back to Home</a>
    </form>
</body>

</html>