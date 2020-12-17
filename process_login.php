<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered_post_info = array(
        'id' => mysqli_real_escape_string($conn , $_POST['id']),
        'pw' => mysqli_real_escape_string($conn , $_POST['pw'])
    );

    $exists_sql = "SELECT EXISTS (SELECT * FROM users WHERE username='{$filtered_post_info['id']}')";

    $exists_result = mysqli_query($conn , $exists_sql);
    
    $exists_row = mysqli_fetch_array($exists_result);

    // 클로저 함수 - 아이디 존재여부 확인
    $validateId = function ($row) use($filtered_post_info){
        if($row !== "1"){
            $_SESSION['error_txt'] = "아이디 또는 비밀번호가 잘못되었습니다.";
            $_SESSION['id'] = $filtered_post_info['id']; // login.php에서 기재했던 아이디를 기억하기 위한 세션사용
            header("location: login.php");
            die();
        }
        else{
            return false;
        }
    };

    $validateId ($exists_row[0]);

    $sql = "SELECT * FROM users WHERE username='{$filtered_post_info['id']}'";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    $filtered_db_info = array(
        'id' => htmlspecialchars($row[1]),
        'pw' => htmlspecialchars($row[2])
    );

    $encryption_pw = md5($filtered_post_info['pw']);

    if($encryption_pw === $filtered_db_info['pw']){
        $_SESSION['user_id'] = $filtered_db_info['id']; // index.php에서 로그인한 사용자에 대한 정보를 기억하기 위한 세션사용
        header("location: index.php");
    }
    else{
        $_SESSION['error_txt'] = "아이디 또는 비밀번호가 잘못되었습니다.";
        $_SESSION['id'] = $filtered_post_info['id']; // login.php에서 기재했던 아이디를 기억하기 위한 세션사용
        header("location: login.php");
    }
?>