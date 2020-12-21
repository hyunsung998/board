<?php
    session_start();

    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();
                
    $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

    if(isset($_SESSION['username'])){
        $_SESSION['error_txt'] = "비정상적인 접근입니다.";
        header("location: index.php");
    }
    else{
        if(!isset($_POST['username'])){
            $_SESSION['error_txt'] = "비정상적인 접근입니다.";
            header("location: index.php");
        }
        else{
            $filtered = array(
                'username' => mysqli_real_escape_string($conn , $_POST['username']),
                'password' => mysqli_real_escape_string($conn , $_POST['password']),
                're_password' => mysqli_real_escape_string($conn , $_POST['re_password'])
            );
        
            $sql = "SELECT EXISTS (SELECT * FROM users WHERE username='{$filtered['username']}')";
        
            $result = mysqli_query($conn , $sql);
        
            $row = mysqli_fetch_array($result);
        
            $exists_username = $row[0];
            
            // username 존재유무 확인 함수
            $validateUserName = function ($exists_username) use($filtered){
                if($exists_username === "1"){
                    $_SESSION['error_txt'] = "동일한 아이디가 존재합니다. 다시 시도해주세요.";
                    // 오류 발생시 입력했던 아이디 기억
                    $_SESSION['username'] = $filtered['username'];
                    header("location: join.php");
                    die();
                }
            };
        
            // 비밀번호가 일치하는지 확인 함수
            $validatePassWord = function ($password , $re_password) use($filtered){
                if($password !== $re_password){
                    $_SESSION['error_txt'] = "비밀번호가 일치하지 않습니다. 다시 시도해주세요.";
                    // 오류 발생시 입력했던 아이디 기억
                    $_SESSION['username'] = $filtered['username'];
                    header("location: join.php");
                    die();
                }
            };
        
            $validateUserName($exists_username);
        
            $validatePassWord($filtered['password'] , $filtered['re_password']);
        
            // DB에 비밀번호를 저장시 해킹의 위험으로 인하여 md5() 단방향 암호화 사용
            $sql = "INSERT INTO users (username , password , created_at) VALUES('{$filtered['username']}' , md5('{$filtered['password']}') , NOW())";
        
            $result = mysqli_query($conn , $sql);
        
            if($result === true){
                header("location: login.php");
            }
            else{
                $_SESSION['error_txt'] = "오류가 발생했습니다. 다시 시도해주세요.";
                header("location: join.php");
            }
        }
        }
?>