<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $sql = "INSERT INTO board(title , description) 
    VALUES('{$_POST['title']}' , '{$_POST['description']}')";

    if($_POST['title'] !== ""){
        $result = mysqli_query($conn , $sql);

        if($result === true){
            echo '게시글이 등록되었습니다. <a href="index.php">게시판으로 이동하기</a>';
        }
        else{
            echo '오류가 발생했습니다.';
            echo mysqli_error($conn);
        }
    }
    else{
        echo '<script>alert("제목을 입력해주세요.")</script>';
        echo '<a href="create.php">다시 작성하기</a>'; 
    }
?>