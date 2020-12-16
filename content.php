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
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>
<?php
$index_css = "./asset/CSS/index.css";
$delete_js = "./asset/JS/delete.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <div class="contScreen">
        <div class="contTitle"><?=$filtered['title']?></div>
        <div class="contDescription"><?=$filtered['description']?></div>
    </div> 

    <!-- 이전 , 수정 , 삭제 버튼 -->
    <div class="button">
        <!-- 검색페이지로 이동하기 위해 키워드 매개변수 사용 -->
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
         
        <!-- 로그인 후 수정 가능하며, keyword 매개변수로 이전페이지로 갈 수 있도록.-->
        <a href="<?php
            if(isset($_SESSION['user_id'])){
                if(isset($_GET['keyword'])){
                    $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);

                    echo "validate_update.php?id={$filtered_id}&keyword={$filtered_keyword}";
                }
                else{
                    echo "validate_update.php?id={$filtered_id}";
                }
            }
            else{
                echo "login.php";
            }
        ?>">
            <input type="<?php
                if(isset($_SESSION['user_id'])){
                    echo "button";
                }
                else{
                    echo "hidden";
                }
            ?>" value="수정" class="modifyBtn btn btn-default">
        </a>

        <form action="process_delete.php" method="post" class="deleteForm">
            <input type="hidden" name="id" value="<?=$filtered_id?>">
            <!-- 검색해서 삭제했을 경우에 대한 처리 / keyword 변수가 존재할 경우 서버로 데이터 보내기 -->
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
            <!-- 유저가 로그인 유무에 따라서 버튼 타입 변경 -->
            <input type="<?php
                if(isset($_SESSION['user_id'])){
                    echo "button";
                }
                else{
                    echo "hidden";
                }
            ?>" value="삭제" class="deleteBtn btn btn-default" >
        </form>
    </div> 
</body>
<script src=<?="./asset/JS/delete.js?".filemtime($delete_js)?>></script>
</html>