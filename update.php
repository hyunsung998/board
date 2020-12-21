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

    if(!isset($_SESSION['username'])){
        $_SESSION['error_txt'] = "비정상적인 접근입니다.";
        header("location: index.php");
        die();
    }
    else{
        if(!isset($_GET['id'])){
            $_SESSION['error_txt'] = "비정상적인 접근입니다.";
            header("location: index.php");
        }
        else{
            $filtered_id = mysqli_real_escape_string($conn , $_GET['id']);

            // topics테이블 id 존재유무 확인
            $sql = "SELECT EXISTS (SELECT * FROM topics WHERE id={$filtered_id})";
    
            $result = mysqli_query($conn , $sql);
    
            $row = mysqli_fetch_array($result);
    
            // url의 매개변수 id 존재유무 확인 함수
            function validateId($exists_row){
                if($exists_row !== "1"){
                    $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
                    header("location: index.php");
                }
            }
    
            validateId($row[0]);
    
            // topics테이블 id에 맞는 row 확인
            $sql = "SELECT * FROM topics WHERE id={$filtered_id}";
    
            $result = mysqli_query($conn , $sql);
    
            $row = mysqli_fetch_array($result);
    
            // topics테이블 필드 중 user_id 선택
            $user_id = htmlspecialchars($row[1]); 
    
            // users테이블 id 존재유무 확인
            $sql = "SELECT EXISTS (SELECT * FROM users WHERE id={$user_id})";
    
            $result = mysqli_query($conn , $sql);
    
            $row = mysqli_fetch_array($result);
    
            validateId($row[0]);
    
            // users테이블 id와 일치하는 row 확인
            $sql = "SELECT * FROM users WHERE id={$user_id}";
    
            $result = mysqli_query($conn , $sql);
    
            $row = mysqli_fetch_array($result);
    
            // users테이블의 id
            $username = htmlspecialchars($row[1]); 
    
            $login_username = $_SESSION['username'];
    
            // 로그인 유저와 작성자가 일치하는지 확인하는 함수
            $validateUser = function ($username , $login_username) use($filtered_id , $conn){
                if($username === $login_username){
                    $sql = "SELECT * FROM topics WHERE id={$filtered_id}";
    
                    $result = mysqli_query($conn , $sql);
    
                    $row = mysqli_fetch_array($result);
    
                    return $row;
                }else{
                    $_SESSION['error_txt'] = "작성자 본인만 수정가능합니다.";
    
                    if(isset($_GET['keyword'])){
                        $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);
    
                        header("location: content.php?id={$filtered_id}&keyword={$filtered_keyword}");
                    }
                    else{
                        header("location: content.php?id={$filtered_id}");
                    }
                }
                
            };
    
            $row = $validateUser($username , $login_username);
    
            $filtered = array(
                'title' => htmlspecialchars($row['title']),
                'description' => htmlspecialchars($row['description'])
            );
        }
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
    <form action="process_update.php" method="POST" id="update_form">
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
        <input id="title" type="text" name="title" placeholder="title" autocomplete="off"
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
        <textarea id="description" name="description" placeholder="description">
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
        <input type="button" value="수정" id="submitBtn" class="btn btn-default">
        <input type="button" value="취소" id="cancelBtn" class="btn btn-default">
    </form>
</body>
<script src=<?="./asset/JS/update.js?".filemtime($update_js)?>></script>
</html>