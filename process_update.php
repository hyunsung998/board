<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $sql = "UPDATE board SET title='{$_POST['title']}' , description='{$_POST['description']}' WHERE id={$_POST['id']}";

    if($_POST['title'] !== ""){
        $result = mysqli_query($conn , $sql);

        if($result === true){
            echo '수정이 완료되었습니다. <a href="index.php">게시판으로 이동하기</a>';
        }
        else{
            echo '오류가 발생했습니다.';
            echo mysqli_error($conn);
        }
    }
    else{
        echo '<script>alert("제목을 입력해주세요.")</script>';
        echo "<a href=\"content.php?id={$_POST['id']}\">다시 수정하기</a>";
    }
?>