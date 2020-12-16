<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }
?>
<?php
    $index_css = "./asset/CSS/index.css";
    $login_css = "./asset/CSS/login.css";
    $login_js = "./asset/JS/login.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href=<?="./asset/CSS/login.css?".filemtime($login_css)?>> 
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
</head>
<body>
    <div class="login_group">
        <form action="process_login.php" method="POST" id="login_form">
            <input type="text" name="id" id="id" placeholder="아이디" autocomplete="off" maxlength="20" value="
<?php
    if(isset($_SESSION['id'])){
        echo $_SESSION['id'];
        unset($_SESSION['id']);
    }
?>
">
            <input type="password" name="pw" id="pw" placeholder="비밀번호" autocomplete="off" maxlength="15">
            <input type="button" value="로그인" id="login_btn">
        </form>
        <div class="join_wrap">
            <a href="join.php">
                <button class="join_page_btn">회원가입</button>
            </a>
        </div>
        <div class="back_btn_wrap">
            <a href="index.php">
                <button class="back_btn">취소</button>
            </a>
        </div>
    </div>
</body>
<script src=<?="./asset/JS/login.js?".filemtime($login_js)?>></script>
</html>