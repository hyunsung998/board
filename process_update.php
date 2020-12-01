<?php
    session_start();
    
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    // 아이디가 데이터베이스 존재하는지 확인
    $exists_sql = "SELECT EXISTS (SELECT * FROM board WHERE id={$filtered_id})";

    $exists_result = mysqli_query($conn , $exists_sql);

    $exists_row = mysqli_fetch_array($exists_result);

    // 1일 경우 true. 즉, 데이터베이스에 존재함. 0일 경우 false
    if($exists_row[0] === "1"){
        $filtered = array(
            'title' => mysqli_real_escape_string($conn , $_POST['title']),
            'description' => mysqli_real_escape_string($conn , $_POST['description'])
        );

        // 타이틀 , 내용의 문자열 개수
        $title_len = mb_strlen($_POST['title']);

        $description_len = mb_strlen($_POST['description']);

        // 타이틀 , 내용이 빈문자열 or 정해놓은 문자의 개수가 맞는지 확인
        if(!empty($filtered['title']) && !empty($filtered['description']) && $title_len > 2 &&  $description_len > 9){
            $sql = "UPDATE board SET title='{$filtered['title']}' , description='{$filtered['description']}' WHERE id={$filtered_id}";
    
            $result = mysqli_query($conn , $sql);
        
            if($result === true){
                //redirect
                echo "<script>alert('게시글이 수정되었습니다.'); location.href=\"index.php\";</script>";
            }
            else{
                //redirect
                $_SESSION['error'] = mysqli_error($conn);
                header("location: error.php"); 
            }
        }
        else{
            //redirect
            $_SESSION['error'] = mysqli_error($conn);
            header("location: error.php");
        }
    }
    else{
        //redirect
        $_SESSION['error'] = mysqli_error($conn);
        header("location: error.php");
    }
?>