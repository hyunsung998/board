<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $filtered_id = mysqli_real_escape_string($conn , $_GET['id']);

    $sql = "SELECT * FROM board WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    $filtered = array(
        'title' => htmlspecialchars($row['title']),
        'description' => htmlspecialchars($row['description'])
    );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- 수정 -->
    <form action="process_update.php" method="post" class="form">
        <input type="hidden" name="id" value="<?=$filtered_id?>">
        <p><input class="title" type="text" name="title" placeholder="title" autocomplete="off" value="<?=$filtered['title']?>"></p>
        <p><textarea class="description" name="description" placeholder="description"><?=$filtered['description']?></textarea></p>
        <p><input class="submitBtn" type="button" value="수정"></p>
    </form>
    <!-- 삭제 -->
    <form action="process_delete.php" method="post" class="deleteForm">
        <input type="hidden" name="id" value="<?=$filtered_id?>">
        <p><input class="deleteBtn" type="button" value="삭제"></p>
    </form>
    <!-- 뒤로 -->
    <div class="backBtn">
        <a href="index.php"><input type="button" value="뒤로"></a>
    </div>  
</body>
<script src="content.js"></script>
</html>