<?php
    session_start();

    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    // 타이틀 , 내용의 문자열 개수
    $title_len = mb_strlen($_POST['title']);

    $description_len = mb_strlen($_POST['description']);

    // 타이틀 , 내용이 빈문자열 or 정해놓은 문자의 개수가 맞는지 확인
    if(!empty($_POST['title']) && !empty($_POST['description']) && $title_len > 2 &&  $description_len > 9){
        $filtered = array(
            'title' => mysqli_real_escape_string($conn , $_POST['title']),
            'description' => mysqli_real_escape_string($conn , $_POST['description'])
        );

        $sql = "INSERT INTO board(title , description , created) 
        VALUES('{$filtered['title']}' , '{$filtered['description']}' , NOW())";

        $result = mysqli_query($conn , $sql);

        if($result === true){
            // redirect
            echo "<script>alert('게시글이 작성되었습니다.'); location.href=\"index.php\";</script>";
        }
        else{
            // redirect
            $_SESSION['error'] = mysqli_error($conn);
            header("location: error.php");
        }
    }
    else{
        // redirect
        $_SESSION['error'] = mysqli_error($conn);
        header("location: error.php");
    }
?>


