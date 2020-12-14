<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    $sql = "SELECT EXISTS (SELECT * FROM user WHERE id='{$filtered_id}')";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    if($row[0] === "1"){
        $sql = "SELECT * FROM user WHERE id='{$filtered_id}'";
        
        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        echo $row[1];
    }
    else{
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>