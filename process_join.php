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

    $sql = "SELECT EXISTS (SELECT * FROM user WHERE id=\"{$filtered['id']}\")";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    if($row[0] !== "1"){
        if($filtered['pw'] === $filtered['re_pw']){
            $sql = "INSERT INTO user (id,pw,re_pw) VALUES('{$filtered['id']}' , '{$filtered['pw']}' , '{$filtered['re_pw']}')";

            $result = mysqli_query($conn , $sql);

            if($result === true){
                header("location: login.php");
            }
            else{
                $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
                header("location: index.php");
            }
        }
        else{
            $_SESSION['error_txt'] = "비밀번호가 일치하지 않습니다. 다시 시도해주세요.";
            header("location: index.php");
        }
    }
    else{
        $_SESSION['error_txt'] = "동일한 아이디가 존재합니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>