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
            $_SESSION['error_txt'] = "수정 가능한 게시글이 존재하지 않습니다.  다시 시도해주세요.";
            $_SESSION['title'] = "{$_POST['title']}";
            $_SESSION['description'] = "{$_POST['description']}";
            header("location: content.php?id={$GLOBALS['filtered_id']}");
            die();
        }
    }

    // 타이틀,내용 양식검사 함수
    function validationText($title , $description , $t_len , $d_len){
        if($title !== "" && $description !== "" && $t_len > 2 && $d_len > 9){
            return true;
        }
        else{
            // redirect
            $_SESSION['error_txt'] = "제목과 내용을 양식에 맞게 수정해주세요.";
            $_SESSION['title'] = "{$_POST['title']}";
            $_SESSION['description'] = "{$_POST['description']}";
            header("location: content.php?id={$GLOBALS['filtered_id']}");
            die();
        }
    }

    validationId($exists_row[0]);

    $title_len = mb_strlen($_POST['title']);

    $description_len = mb_strlen($_POST['description']);

    validationText($_POST['title'] , $_POST['description'] , $title_len , $description_len);

    $filtered = array(
        'title' => mysqli_real_escape_string($conn , $_POST['title']),
        'description' => mysqli_real_escape_string($conn , $_POST['description'])
    );

    $sql = "UPDATE topic SET title='{$filtered['title']}' , description='{$filtered['description']}' WHERE id={$filtered_id}";
    
    $result = mysqli_query($conn , $sql);
        
    if($result === true){
        //redirect
        $_SESSION['success_txt'] = "게시글이 수정되었습니다.";
        header("location: index.php");
    }
    else{
        //redirect
        $_SESSION['error_txt'] = "게시글 수정에 실패했습니다. 다시 시도해주세요.";
        $_SESSION['title'] = "{$filtered['title']}";
        $_SESSION['description'] = "{$filtered['description']}";
        header("location: content.php?id={$filtered_id}");
    }
?>