<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered = array(
        'id' => mysqli_real_escape_string($conn , $_POST['id']),
        'pw' => mysqli_real_escape_string($conn , $_POST['pw']),
        're_pw' => mysqli_real_escape_string($conn , $_POST['re_pw'])
    );

    $sql = "SELECT EXISTS (SELECT * FROM users WHERE id='{$filtered['id']}')";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // 아이디 존재유무 확인 함수
    $validateId = function ($exists_id) use($filtered){
        if($exists_id === "1"){
            $_SESSION['error_txt'] = "동일한 아이디가 존재합니다. 다시 시도해주세요.";
            // 오류 발생시 입력했던 아이디 기억
            $_SESSION['id'] = $filtered['id'];
            header("location: join.php");
            die();
        }
    };

    // 비밀번호가 일치하는지 확인 함수
    function validatePw($pw , $re_pw){
        if($pw !== $re_pw){
            $_SESSION['error_txt'] = "비밀번호가 일치하지 않습니다. 다시 시도해주세요.";
            header("location: join.php");
            die();
        }
    }

    $validateId($row[0]);

    validatePw($filtered['pw'] , $filtered['re_pw']);

    // DB에 비밀번호를 저장시 해킹의 위험으로 인하여 md5() 단방향 암호화 사용
    $sql = "INSERT INTO users (username , password) VALUES('{$filtered['id']}' , md5('{$filtered['pw']}'))";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        header("location: login.php");
    }
    else{
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: join.php");
    }
?>