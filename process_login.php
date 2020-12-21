<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered_post_info = array(
        'username' => mysqli_real_escape_string($conn , $_POST['username']),
        'password' => mysqli_real_escape_string($conn , $_POST['password'])
    );

    $sql = "SELECT EXISTS (SELECT * FROM users WHERE username='{$filtered_post_info['username']}')";

    $result = mysqli_query($conn , $sql);
    
    $row = mysqli_fetch_array($result);

    $username = $row[0];

    // 아이디 존재여부 확인 함수
    $validateUserName = function ($username) use($filtered_post_info){
        if($username !== "1"){
            $_SESSION['error_txt'] = "아이디 또는 비밀번호가 잘못되었습니다.";
            $_SESSION['username'] = $filtered_post_info['id']; // login.php에서 기재했던 아이디를 기억하기 위한 세션사용
            header("location: login.php");
            die();
        }
        else{
            return false;
        }
    };

    $validateUserName ($username);

    $sql = "SELECT * FROM users WHERE username='{$filtered_post_info['username']}'";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    $username = $row[1];

    $password = $row[2];

    $filtered_db_info = array(
        'username' => htmlspecialchars($username),
        'password' => htmlspecialchars($password)
    );

    $encryption_pw = md5($filtered_post_info['password']);

    if($encryption_pw === $filtered_db_info['password']){
        $_SESSION['username'] = $filtered_db_info['username']; // index.php에서 로그인한 사용자에 대한 정보를 기억하기 위한 세션사용
        header("location: index.php");
    }
    else{
        $_SESSION['error_txt'] = "아이디 또는 비밀번호가 잘못되었습니다.";
        $_SESSION['username'] = $filtered_post_info['username']; // login.php에서 기재했던 아이디를 기억하기 위한 세션사용
        header("location: login.php");
    }
?>