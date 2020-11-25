<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $filtered = array(
        'title' => mysqli_real_escape_string($conn , $_POST['title']),
        'description' => mysqli_real_escape_string($conn , $_POST['description']),
        'id' => mysqli_real_escape_string($conn , $_POST['id'])
    );

    $sql = "UPDATE board SET title='{$filtered['title']}' , description='{$filtered['description']}' WHERE id={$filtered['id']}";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        header("Location: index.php");
    }
    else{
        echo '오류가 발생했습니다.';
        echo error_log(mysqli_error($conn)); 
    }
?>