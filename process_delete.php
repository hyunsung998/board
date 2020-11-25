<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    $sql = "DELETE FROM board WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        header("location: index.php");
    }
    else{
        echo "오류가 발생했습니다.";
        echo error_log(ysqli_error($conn)); 
    }
?>