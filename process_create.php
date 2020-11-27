<?php
    session_start();

    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $title_len = mb_strlen($_POST['title']);

    $description_len = mb_strlen($_POST['description']);

    // 타이틀과 내용이 빈 값 또는 정해놓은 문자의 개수가 맞는지 확인
    if(!empty($_POST['title']) && !empty($_POST['description']) && $title_len > 2 &&  $description_len > 14){
        $filtered = array(
            'title' => mysqli_real_escape_string($conn , $_POST['title']),
            'description' => mysqli_real_escape_string($conn , $_POST['description'])
        );

        $sql = "INSERT INTO board(title , description , created) 
        VALUES('{$filtered['title']}' , '{$filtered['description']}' , NOW())";

        $result = mysqli_query($conn , $sql);

        if($result === true){
            echo "<script>alert('게시글이 작성되었습니다.'); location.href=\"index.php\";</script>";
        }
        else{
            // redirect
        }
    }
    else{
        // redirect

        // $_SESSION['error'] = mysqli_error($conn);
        // header("location: error.php");
       
        // echo "<script>alert('제목과 내용을 다시 입력해주세요.'); location.href=\"index.php\";</script>";
    }
?>


