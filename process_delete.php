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
            $_SESSION['error_txt'] = "게시글이 존재하지 않습니다.";
            header("location: index.php");
        }
    }

    validationId($exists_row[0]);

    $sql = "DELETE FROM topic WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        //redirect
        // 제목을 검색하고 클릭한 게시글이 삭제 되었을 때 검색 페이지로 이동할 수 있도록 처리
        if(isset($_POST['keyword'])){
            $filtered_keyword = mysqli_real_escape_string($conn , $_POST['keyword']);

            header("location: index.php?keyword={$filtered_keyword}");
        }
        else{
            header("location: index.php");
        }
    }
    else{
        //redirect 
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>