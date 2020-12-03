<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");
    
    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    $exists_sql = "SELECT EXISTS (SELECT * FROM topic WHERE id={$filtered_id})";

    $exists_result = mysqli_query($conn , $exists_sql);

    $exists_row = mysqli_fetch_array($exists_result);

    // 아이디 존재여부 함수
    function validationId($target_row){
        if($target_row === "1"){
            return true;
        }
        else{
            //redirect
            $_SESSION['error_txt'] = "삭제 가능한 게시글이 존재하지 않습니다.  다시 시도해주세요.";
            header("location: index.php");
            die();
        }
    }

    validationId($exists_row[0]);

    $sql = "DELETE FROM topic WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        //redirect
        $_SESSION['success_txt'] = "게시글이 삭제되었습니다.";
        header("location: index.php");
    }
    else{
        //redirect 
        $_SESSION['error_txt'] = "게시글을 삭제하는 과정에서 오류가 발생했습니다. 다시 시도해주세요.";
        header("location: content.php?id={$filtered_id}");
    }
?>