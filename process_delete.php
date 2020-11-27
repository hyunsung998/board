<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");
    
    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    // 아이디가 데이터베이스 존재하는지 확인
    $exists_sql = "SELECT EXISTS (SELECT * FROM board WHERE id={$filtered_id})";

    $exists_result = mysqli_query($conn , $exists_sql);

    $exists_row = mysqli_fetch_array($exists_result);

    // 1일 경우 true 0일 경우 false
    if($exists_row[0] === "1"){
        $sql = "DELETE FROM board WHERE id={$filtered_id}";

        $result = mysqli_query($conn , $sql);

        if($result === true){
            header("location: index.php");
        }
        else{
            //redirect 
        }
    }
    else{
        //redirect
    }
?>