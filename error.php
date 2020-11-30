<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <body>
        <?php echo $_SESSION['error'];?>
        <br>오류가 발생하였습니다. 관리자에게 문의해주세요.
        <br><a href="index.php">게시판으로 이동</a>
    </body>
</html>