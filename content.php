<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }
    
    if(isset($_SESSION['success_txt'])){
        echo "<script>alert('{$_SESSION['success_txt']}')</script>";
        unset($_SESSION['success_txt']);
    }
?>
<?php
    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
        
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered_id = mysqli_real_escape_string($conn , $_GET['id']);

    // 아이디가 데이터베이스 존재하는지 확인
    $exists_sql = "SELECT EXISTS (SELECT * FROM topic WHERE id={$filtered_id})";

    $exists_result = mysqli_query($conn , $exists_sql);

    $exists_row = mysqli_fetch_array($exists_result);

    // 1일 경우 true. 즉, 데이터베이스에 존재함. 0일 경우 false
    if($exists_row[0] === "1"){
        $sql = "SELECT * FROM topic WHERE id={$filtered_id}";

        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        $filtered = array(
            'title' => htmlspecialchars($row['title']),
            'description' => htmlspecialchars($row['description'])
        );
    }
    else{
        //redirect
        $_SESSION['error_txt'] = "게시글을 찾는데 오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>
<?php
$update = "update.js";
$delete = "delete.js";
$css = "index.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href="<?=$css.'?'.filemtime($css)?>">
</head>
<body>
    <!-- 수정 -->
    <form action="process_update.php" method="post" class="form">
        <input type="hidden" name="id" value="<?=$filtered_id?>">
        <p><input class="title" type="text" name="title" placeholder="title" autocomplete="off" value="
<?php 
    if(isset($_SESSION['title'])){
        echo $_SESSION['title']; 
        unset($_SESSION['title']);
    }
    else{
        echo $filtered['title'];
    }
?>"></p>
        <p><textarea class="description" name="description" placeholder="description">
<?php 
    if(isset($_SESSION['description'])){
        echo $_SESSION['description']; 
        unset($_SESSION['description']);
    }
    else{
        echo $filtered['description'];
    }
?></textarea></p>
        <p><input class="submitBtn" type="submit" value="수정"></p>
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
<script src=<?=$delete.'?'.filemtime($delete)?>></script>
</html>