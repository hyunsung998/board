<?php
    session_start();
    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }
?>
<?php
    $css = "index.css";
    $create = "create.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link rel="stylesheet" href=<?=$css.'?'.filemtime($css)?>>
</head>
<body>
    <!-- 게시글 생성 -->
    <form action="process_create.php" method="POST" class="form" >

    <p><input class="title" type="text" name="title" placeholder="title" autocomplete="off" value="<?php
        if(isset($_SESSION['title'])){
            echo $_SESSION['title'];
            unset($_SESSION['title']);
        }
        ?>"></p>
    <p><textarea class="description" name="description" placeholder="description">
<?php 
    if(isset($_SESSION['description'])){
        echo $_SESSION['description']; 
        unset($_SESSION['description']);
        }
?></textarea></p>

        <p><input class="submitBtn" type="submit" value="등록"></p>
    </form>

    <!-- 뒤로 -->
    <div class="backBtn">
        <a href="index.php"><input type="button" value="뒤로"></a>
    </div>
</body>
<script src=<?=$create.'?'.filemtime($create)?>></script>
</html>