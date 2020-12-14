<?php
    $index_css = "./asset/CSS/index.css";
    $join_css = "./asset/CSS/join.css";
    $join_js = "./asset/JS/join.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href=<?="./asset/CSS/join.css?".filemtime($join_css)?>> 
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
</head>
<body>
    <div class="join_group"> 
        <form action="process_join.php" method="POST" id="join_form">
            <fieldset>
                <div class="info"> 
                    <div class="join_txt">아이디 입력</div>
                    <div class="form_txt">
                        <input type="text" class="join_input id" name="id" id="id" placeholder="아이디를 입력해 주세요." autocomplete="off" maxlength="20">
                        <div class="msgbox">
                            <p>5~20자 영문, 숫자로 입력해 주세요.</p>
                            <p id="id_able"></p>
                            <p id="id_unable"></p>
                        </div>
                    </div>
                </div>
                <div class="info">
                    <div class="join_txt">비밀번호 입력</div>
                    <div class="form_txt form_txt_pw">
                        <input type="password" class="join_input pw" name="pw" id="pw" placeholder="비밀번호를 입력해 주세요." autocomplete="off" maxlength="15">
                        <input type="password" class="join_input pw" name="re_pw" id="re_pw" placeholder="비밀번호를 재확인해 주세요." autocomplete="off" maxlength="15">
                        <div class="msgbox">
                                <p>8~15자 영문, 숫자, 특수문자 조합입니다.</p>
                                <p id="pw_able"></p>
                                <p id="pw_unable"></p>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="join_btn_wrap">
                <input type="button" id="join_btn" value="가입하기">
            </div>
        </form>
        <div class="cancel_btn_wrap">
            <a href="login.php"><button class="cancel_btn">취소하기</button></a>
        </div>
    </div>
</body>
<script src=<?="./asset/JS/join.js?".filemtime($join_js)?>></script>
</html>