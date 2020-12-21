<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");
    
    if(!isset($_SESSION['username'])){
        $_SESSION['error_txt'] = "비정상적인 접근입니다.";
        header("location: index.php");
    }
    else{
        $filtered_id = mysqli_real_escape_string($conn , $_POST['id']);

        $sql = "SELECT EXISTS (SELECT * FROM topics WHERE id={$filtered_id})";

        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        function validateId($row){
            if($row !== "1"){
                $_SESSION['error_txt'] = "게시글이 존재하지 않습니다.";
                header("location: index.php");
            }
        }

        validateId($row[0]);

        // topics테이블 id에 맞는 row 확인
        $sql = "SELECT * FROM topics WHERE id={$filtered_id}";

        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        // topics테이블 필드 중 user_id 선택
        $user_id = htmlspecialchars($row[1]);

        // users테이블 id 존재유무 확인
        $sql = "SELECT EXISTS (SELECT * FROM users WHERE id={$user_id})";

        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        validateId($row[0]);

        // users테이블 id와 일치하는 row 확인
        $sql = "SELECT * FROM users WHERE id={$user_id}";

        $result = mysqli_query($conn , $sql);

        $row = mysqli_fetch_array($result);

        // users테이블의 username
        $username = htmlspecialchars($row[1]); 

        $login_username = $_SESSION['username'];

        // 로그인 유저와 작성자가 일치하는지 확인하는 함수
        $validateUser = function ($username , $login_username) use($filtered_id , $conn){
            if($username === $login_username){
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
                if(isset($_POST['keyword'])){
                    $filtered_keyword = mysqli_real_escape_string($conn , $_POST['keyword']);

                    header("location: content.php?id={$filtered_id}&keyword={$filtered_keyword}");
                }
                else{
                    header("location: content.php?id={$filtered_id}");
                }
            }
        };

        $validateUser($username , $login_username);
    }
?>