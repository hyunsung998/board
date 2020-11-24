<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="process_create.php" method="post">
        <p><input type="text" name="title" placeholder="title" autocomplete="off"></p>
        <p><textarea name="description" placeholder="description"></textarea></p>
        <p>
            <span>
                <button><a href="index.php">뒤로</a></button>
                <input type="submit" value="등록">
            </span>
        </p>
    </form>
</body>
</html>