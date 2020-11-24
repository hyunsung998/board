<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $sql = "SELECT * FROM board WHERE id={$_GET['id']}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- 수정 -->
    <form action="process_update.php" method="post">
        <input type="hidden" name="id" value="<?=$_GET['id']?>">
        <p><input type="text" name="title" placeholder="title" autocomplete="off" value="<?=$row['title']?>"></p>
        <p><textarea name="description" placeholder="description"><?=$row['description']?></textarea></p>
        <p><button><a href="index.php">뒤로</a></button></p>
        <p><input type="submit" value="수정"></p>
    </form>
    <!-- 삭제 -->
    <form action="process_delete.php" method="post">
        <input type="hidden" name="id" value="<?=$_GET['id']?>">
        <p><button>삭제</button></p>
    </form>
</body>
</html>