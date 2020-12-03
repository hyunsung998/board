<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    // 타이틀,내용 양식검사 함수
    function validationText($title , $description , $t_len , $d_len){
        if($title !== "" && $description !== "" && $t_len > 2 && $d_len > 9){
            return true;
        }
        else{
            // redirect
            $_SESSION['error_txt'] = "제목과 내용을 양식에 맞게 작성해주세요.";
            $_SESSION['title'] = "{$_POST['title']}";
            $_SESSION['description'] = "{$_POST['description']}";
            header("location: create.php");
            die();
        }
    }

    $title_len = mb_strlen($_POST['title']);

    $description_len = mb_strlen($_POST['description']);

    validationText($_POST['title'] , $_POST['description'] , $title_len , $description_len);

    $filtered = array(
        'title' => mysqli_real_escape_string($conn , $_POST['title']),
        'description' => mysqli_real_escape_string($conn , $_POST['description'])
    );

    $sql = "INSERT INTO topic(title , description , created) 
    VALUES('{$filtered['title']}' , '{$filtered['description']}' , NOW())";

    $result = mysqli_query($conn , $sql);

    if($result === true){
         // redirect
        $_SESSION['success_txt'] = "게시글이 작성되었습니다.";
        header("location: index.php");
    }
    else{
        // redirect
        $_SESSION['error_txt'] = "게시글을 작성하는 과정에서 오류가 발생했습니다. 다시 시도해주세요.";
        $_SESSION['title'] = "{$filtered['title']}";
        $_SESSION['description'] = "{$filtered['description']}";
        header("location: create.php");
    }
?>


