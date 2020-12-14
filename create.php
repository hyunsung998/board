<?php
    session_start();
    
    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }
?>
<?php
    $index_css = "./asset/CSS/index.css";
    $create_js = "./asset/JS/create.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <form action="process_create.php" method="POST" class="form">
        <input class="title" type="text" name="title" placeholder="title" autocomplete="off" value="<?php
                // 오류 발생시 입력한 제목과 내용을 기억하기 위해 session 사용
                if(isset($_SESSION['title'])){
                    echo $_SESSION['title'];
                    unset($_SESSION['title']);
                }
                ?>">
        <textarea class="description" name="description" placeholder="description"><?php
        // 오류로 입력한 제목과 내용을 기억하기 위해 session 사용
        if(isset($_SESSION['description'])){
            echo $_SESSION['description'];
            unset($_SESSION['description']);
        }?>
</textarea>
        <input class="submitBtn btn btn-default" type="button" value="등록">
        <input type="button" value="취소" class="cancelBtn btn btn-default">
    </form>
</body>
<script src=<?="./asset/JS/create.js?".filemtime($create_js)?>></script>
</html>