<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- 게시글 생성 -->
    <form action="process_create.php" method="post" class="form" >
        <p><input class="title" type="text" name="title" placeholder="title" autocomplete="off" autofocus></p>
        <p><textarea class="description" name="description" placeholder="description"></textarea></p>
        <p><input class="submitBtn" type="button" value="등록"></p>
    </form>
    <!-- 뒤로 -->
    <div class="backBtn">
        <a href="index.php"><input type="button" value="뒤로"></a>
    </div>
</body>
<script src="creation.js"></script>
</html>