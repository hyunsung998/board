<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    // 아이디가 데이터베이스 존재하는지 확인
    $exists_sql = "SELECT EXISTS (SELECT * FROM board WHERE id={$filtered_id})";

    $exists_result = mysqli_query($conn , $exists_sql);

    $exists_row = mysqli_fetch_array($exists_result);

    // 1일 경우 true 0일 경우 false
    if($exists_row[0] === "1"){
        $filtered = array(
            'title' => mysqli_real_escape_string($conn , $_POST['title']),
            'description' => mysqli_real_escape_string($conn , $_POST['description'])
        );

        // 타이틀과 내용이 빈 값이 아닌지 확인
        if(!empty($filtered['title']) && !empty($filtered['description'])){
            $sql = "UPDATE board SET title='{$filtered['title']}' , description='{$filtered['description']}' WHERE id={$filtered_id}";
    
            $result = mysqli_query($conn , $sql);
        
            if($result === true){
                header("Location: index.php");
            }
            else{
                //redirect 
            }
        }
        else{
            //redirect
        }
    }
    else{
        //redirect
    }
?>