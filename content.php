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
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>
<?php
$index_css = "index.css";
$update_js = "update.js";
$delete_js = "delete.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href="<?=$index_css.'?'.filemtime($index_css)?>">
</head>
<body>
    <div class="contScreen">
        <div class="contTitle"><?=$filtered['title']?></div>
        <div class="contDescription"><?=$filtered['description']?></div>
    </div> 

    <!-- 수정 , 삭제 , 메인이동 버튼 -->
    <div class="button">
        <!-- 검색한 게시글을 클릭한 뒤 검색내역 페이지로 이동할 수 있도록 처리 -->
        <a href="
            <?php
                if(isset($_GET['keyword'])){
                    echo "index.php?keyword={$_GET['keyword']}";
                }
                else{
                    echo "index.php";
                }
            ?>">
            <input type="button" value="<?php
                if(isset($_GET['keyword'])){
                    echo "이전";
                }
                else{
                    echo "목록";
                }
            ?>">
        </a>

        <a href="update.php?id=<?=$filtered_id?>">
            <input type="button" value="수정" class="modifyBtn">
        </a>

        <form action="process_delete.php" method="post" class="deleteForm">
            <input type="hidden" name="id" value="<?=$filtered_id?>">
            <!-- 제목을 검색하고 클릭한 게시글이 삭제 되었을 때 검색 페이지로 이동할 수 있도록 처리 -->
            <input type="hidden" name="keyword" value="<?php
                if(isset($_GET['keyword'])){
                    echo $_GET['keyword'];
                }
            ?>">
            <input class="deleteBtn" type="button" value="삭제">
        </form> 
    </div>
</body>
<script src=<?=$delete_js.'?'.filemtime($delete_js)?>></script>
</html>