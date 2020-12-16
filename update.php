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
$index_css = "./asset/CSS/index.css";
$update_js = "./asset/JS/update.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <form action="process_update.php" method="POST" class="form">
        <input type="hidden" name="id" value="<?=$filtered_id?>">
        <!-- 검색해서 수정했을 경우에 대한 처리 / keyword 변수가 존재할 경우 서버로 데이터 보내기 -->
        <input type="hidden" name="<?php
            if(isset($_GET['keyword'])){
                echo "keyword";
            }
        ?>" value="<?php
            if(isset($_GET['keyword'])){
                $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);
                echo $filtered_keyword;
            }
        ?>">
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
        <input type="button" value="등록" class="submitBtn btn btn-default">
        <input type="button" value="취소" class="cancelBtn btn btn-default">
    </form>
</body>
<script src=<?="./asset/JS/update.js?".filemtime($update_js)?>></script>
</html>