<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");
    
    $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

    // topic 테이블 id 존재유무 확인
    $sql = "SELECT EXISTS (SELECT * FROM topics WHERE id={$filtered_id})";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // 아이디 존재여부 확인 함수
    function validateId($exists_row){
        if($exists_row !== "1"){
            $_SESSION['error_txt'] = "게시글이 존재하지 않습니다.";
            header("location: index.php");
        }
    }

    validateId($row[0]);

    // topic 테이블 id에 맞는 row 확인
    $sql = "SELECT * FROM topics WHERE id={$filtered_id}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // topic 테이블 필드 중 user_id 선택
    $user_id = htmlspecialchars($row[1]);

    // user 테이블 id 존재유무 확인
    $sql = "SELECT EXISTS (SELECT * FROM users WHERE id={$user_id})";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    validateId($row[0]);

    // user 테이블 u_id와 일치하는 row 확인
    $sql = "SELECT * FROM users WHERE id={$user_id}";

    $result = mysqli_query($conn , $sql);

    $row = mysqli_fetch_array($result);

    // user 테이블의 id
    $writer = htmlspecialchars($row[1]); 

    $loginUser = $_SESSION['user_id'];

    // 로그인 유저와 작성자가 일치하는지 확인하는 클로저 함수
    $validateUser = function ($writer , $loginUser) use($filtered_id , $conn){
        if($writer === $loginUser){
            $sql = "DELETE FROM topics WHERE id={$filtered_id}";

            $result = mysqli_query($conn , $sql);

            if($result === true){
                // 제목을 검색하고 클릭한 게시글이 삭제 되었을 때 검색 페이지로 이동할 수 있도록 처리
                if(isset($_POST['keyword'])){
                    $filtered_keyword = mysqli_real_escape_string($conn , $_POST['keyword']);

                    header("location: index.php?keyword={$filtered_keyword}");
                }
                else{
                    header("location: index.php");
                }
            }
            else{
                $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
                header("location: index.php");
            }
        }else{
            $_SESSION['error_txt'] = "작성자 본인만 삭제가능합니다.";
            header("location: content.php?id={$filtered_id}");
        }
    };

    $validateUser($writer , $loginUser);
?>