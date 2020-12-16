<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered_id = mysqli_real_escape_string($conn , $_GET['id']);

    // topic 테이블 id 존재유무 확인
    $sql = "SELECT EXISTS (SELECT * FROM topic WHERE id={$filtered_id})";

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

    // topic 테이블 id에 맞는 row 확인
    $sql = "SELECT * FROM topic WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // topic 테이블 필드 중 user_id 선택
    $user_id = htmlspecialchars($row[4]); 

    // user 테이블 id 존재유무 확인
    $sql = "SELECT EXISTS (SELECT * FROM user WHERE u_id={$user_id})";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    validateId($row[0]);

    // user 테이블 u_id와 일치하는 row 확인
    $sql = "SELECT * FROM user WHERE u_id={$user_id}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // user 테이블의 id
    $writer = htmlspecialchars($row[1]); 

    $loginUser = $_SESSION['user_id'];

    // 로그인 유저와 작성자가 일치하는지 확인하는 클로저 함수
    $validateUser = function ($writer , $loginUser) use($filtered_id , $conn){
        if($writer === $loginUser){
            $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);

            if(isset($_GET['keyword'])){
                header("location: update.php?id={$filtered_id}&keyword={$filtered_keyword}");
            }
            else{
                header("location: update.php?id={$filtered_id}");
            }
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

    $validateUser($writer , $loginUser);
?>