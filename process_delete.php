<?php
    $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

    $sql = "DELETE FROM board WHERE id={$_POST['id']}";

    $result = mysqli_query($conn , $sql);

    if($result === true){
        echo "삭제되었습니다.<a href=\"index.php\">게시판으로 이동하기</a>";
    }
    else{
        echo "오류가 발생했습니다.";
        echo mysqli_error($conn);
    }
?>