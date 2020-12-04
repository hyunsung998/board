<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
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
        $_SESSION['error_txt'] = "게시글이 존재하지 않습니다.";
        header("location: index.php");
    }
?>
<?php
$index_css = "index.css";
$update_js = "update.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
    <link rel="stylesheet" href="<?=$index_css.'?'.filemtime($index_css)?>">
</head>
<body>
    <form action="process_update.php" method="POST" class="form">
        <input type="hidden" name="id" value="<?=$filtered_id?>">
        <input class="title" type="text" name="title" placeholder="title" autocomplete="off"
        value="<?php
            // 오류 발생시 입력했던 내용을 기억하기위해 session에 저장
            if(isset($_SESSION['title'])){
                echo $_SESSION['title'];
                unset($_SESSION['title']);
            }
            else{
                echo $filtered['title'];  
            }
        ?>">
        <textarea class="description" name="description" placeholder="description">
<?php
            // 오류 발생시 입력했던 내용을 기억하기위해 session에 저장
            if(isset($_SESSION['description'])){
                echo $_SESSION['description'];
                unset($_SESSION['description']);
            }
            else{
                echo $filtered['description'];  
            }
?>
</textarea>
        <input type="button" value="등록" class="submitBtn">
    </form>
    <input type="button" value="취소" class="cancelBtn">
</body>
<script src=<?=$update_js.'?'.filemtime($update_js)?>></script>
</html>