<!DOCTYPE html>
<html>

<head>
    <title>Create Topic</title>
    <link rel="stylesheet" href="../assets/AddTopic.css">
</head>

<body>
    <h1>Would You Like to Create a New Topic?</h1>
    <form action="../includes/api.php?action=new_topic" method="POST">

        <input type="text" name="title" required placeholder="Enter topic title"><br>
        <button type="submit">Create Topic</button>
    </form>
</body>

</html>