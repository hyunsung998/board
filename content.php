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

    $sql = "SELECT EXISTS (SELECT * FROM topics WHERE id={$filtered_id})";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    if($row[0] === "1"){
        $sql = "SELECT * FROM topics WHERE id={$filtered_id}";

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
$index_css = "./asset/CSS/index.css";
$content_css = "./asset/CSS/content.css";
$delete_js = "./asset/JS/delete.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href=<?="./asset/CSS/content.css?".filemtime($content_css)?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <div class="contScreen">
        <div class="contTitle"><?=$filtered['title']?></div>
        <div class="contDescription"><?=$filtered['description']?></div>
    </div> 

    <!-- 이전 , 수정 , 삭제 버튼 -->
    <div class="button">
        <!-- 이전 버튼 -->
        <a href="<?php
            if(isset($_GET['keyword'])){
                $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);

                echo "index.php?id={$filtered_id}&keyword={$filtered_keyword}";
            }
            else{
                echo "index.php";
            }
        ?>">
            <input type="button" value="이전" class="listBtn btn btn-default">
        </a>
         
        <!-- 수정 버튼 -->
        <?php
            if(isset($_SESSION['username'])){
        ?>
        <a href="<?php
                if(isset($_GET['keyword'])){
                    $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);

                    echo "update.php?id={$filtered_id}&keyword={$filtered_keyword}";
                }
                else{
                    echo "update.php?id={$filtered_id}";
                }
        ?>">
            <input type="button" value="수정" class="modifyBtn btn btn-default">
        </a>
        <?php
            }
        ?>

        <!-- 삭제 버튼  -->
        <?php
            if(isset($_SESSION['username'])){
        ?>
        <form action="process_delete.php" method="POST" id="deleteForm">
            <input type="hidden" name="id" value="<?=$filtered_id?>">
            <!-- 검색해서 삭제했을 경우에 대한 처리 -->
            <input type="hidden" name="<?php
            if(isset($_GET['keyword'])){
                echo "keyword";
            }
            ?>"
            value="<?php
                if(isset($_GET['keyword'])){
                    $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);
                    echo $filtered_keyword;
                }
            ?>">
            <input type="button" value="삭제" id="deleteBtn" class="btn btn-default" >
        </form>
        <?php
            }
        ?>
    </div> 
</body>
<script src=<?="./asset/JS/delete.js?".filemtime($delete_js)?>></script>
</html>