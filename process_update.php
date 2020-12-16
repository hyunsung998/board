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

    // 아이디 존재여부 확인 함수
    function validationId($target_row){
        if($target_row !== "1"){
            //redirect
            $_SESSION['error_txt'] = "게시글이 존재하지 않습니다.";
            header("location: index.php");
        }
    }

    function checkText($text){
        return isset($text);
    }

    function checkTextLen($text , $num){
        return mb_strlen($text) > $num;
    }
    
    $validationText = function ($title , $description) use($filtered_id){
        $result = checkText($title) && checkText($description) && checkTextLen($title , 2) && checkTextLen($description , 9);

        if(!$result){
            // redirect
            $_SESSION['error_txt'] = "제목과 내용을 양식에 맞게 작성해주세요.";

            // 입력한 제목과 내용을 기억하기 위해 session 사용
            $_SESSION['title'] = "{$title}";
            $_SESSION['description'] = "{$description}";
            header("location: update.php?id={$filtered_id}");
            die();
        }
    };

    validationId($exists_row[0]);

    $validationText($_POST['title'] , $_POST['description']);

    $filtered = array(
        'title' => mysqli_real_escape_string($conn , $_POST['title']),
        'description' => mysqli_real_escape_string($conn , $_POST['description'])
    );

    $sql = "UPDATE topic SET title='{$filtered['title']}' , description='{$filtered['description']}' WHERE id={$filtered_id}";
    
    $result = mysqli_query($conn , $sql);
        
    if($result === true){
        // 검색했을 때, 안했을 때 수정 시 로케이션 경로 변경
        if(isset($_POST['keyword'])){
            $filtered_keyword = mysqli_real_escape_string($conn , $_POST['keyword']);
            header("location: content.php?id={$filtered_id}&keyword={$filtered_keyword}");
        }
        else{
            header("location: content.php?id={$filtered_id}");
        }
    }
    else{
        $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
        header("location: index.php");
    }
?>