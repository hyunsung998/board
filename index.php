<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $sql = "SELECT * FROM board";

    $result = mysqli_query($conn , $sql);

    $list = '';

    while($row = mysqli_fetch_array($result)){
        $list = $list."<li><a href=\"content.php?id={$row['id']}\">{$row['title']}</a></li>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>게시판</h1>
    <ol>
        <?=$list?>
    </ol>
    <h3><a href="create.php">게시글 작성</a></h3>
</body>
</html>