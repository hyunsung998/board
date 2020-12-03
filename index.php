<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        // unset($_SESSION['error_txt']);
    }

    if(isset($_SESSION['success_txt'])){
        echo "<script>alert('{$_SESSION['success_txt']}')</script>";
        unset($_SESSION['success_txt']);
    }
?>
<?php
    $css = "index.css";
    $search = "search.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href=<?=$css.'?'.filemtime($css)?>>
</head>
<body>
    <div class="headerTitle">
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>
    <div class="searchCont">
        <form action="index.php" method="GET" class="form">
            <input type="text" name="title" autocomplete="off" placeholder="검색할 제목을 입력해주세요." class="title" value="
<?php 
                if(isset($_SESSION['search_txt'])){
                    echo $_SESSION['search_txt'];
                    unset($_SESSION['search_txt']);
                }
                else if(isset($_GET['title'])){
                    echo $_GET['title'];
                }
?>">
            <input type="button" value="검색" class="submitBtn">
        </form>
    </div>
    <table>
        <tr>
            <td>No</td>
            <td>제목</td>
            <td>등록일</td>
        </tr>
        <?php
            require __DIR__ . '/vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

            $dotenv->load();
                
            $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");

            if(isset($_GET['title'])){
                $filtered_title = mysqli_real_escape_string($conn , $_GET['title']); 

                $sql = "SELECT * FROM topic WHERE title LIKE '%{$filtered_title}%'";

                $result = mysqli_query($conn , $sql);

                $resultNumRows = mysqli_num_rows($result); 
                                
                $resultAllRows = mysqli_fetch_all($result);
        
                if($resultNumRows !== 0){
                    for($i=0; $i<$resultNumRows; $i++){
                        $id = $resultAllRows[$i][0];
                        $title = htmlspecialchars($resultAllRows[$i][1]); 
                        $created = $resultAllRows[$i][3];
        ?>
                <tr>
                    <td><?=$id?></td>
                    <td>
                        <?="<a href=\"content.php?id={$id}\">{$title}</a>"?>
                    </td>
                    <td><?=$created?></td>
                </tr> 
        <?php
                    }
                }
                else{
                    //redirect
                    $_SESSION['error_txt'] = "검색결과가 없습니다.";
                    $_SESSION['search_txt'] = $_GET['title'];
                    header("location: index.php");
                }
            }
            else{
                if(isset($_SESSION['error_txt'])){
                    unset($_SESSION['error_txt']);
                }else{
                    $sql = "SELECT * FROM topic";

                    $result = mysqli_query($conn , $sql);

                    $resultNumRows = mysqli_num_rows($result); 
                                    
                    $resultAllRows = mysqli_fetch_all($result);
            
                    if($resultNumRows !== 0){
                        for($i=0; $i<$resultNumRows; $i++){
                            $id = $resultAllRows[$i][0];
                            $title = htmlspecialchars($resultAllRows[$i][1]); 
                            $created = $resultAllRows[$i][3];
        ?>
                    <tr>
                        <td><?=$id?></td>
                        <td>
                            <?="<a href=\"content.php?id={$id}\">{$title}</a>"?>
                        </td>
                        <td><?=$created?></td>
                    </tr> 
        <?php
                        }
                    }
                    else{
                        //redirect
                        $_SESSION['error_txt'] = "게시글을 불러오는데 오류가 발생했습니다.";
                        header("location: index.php");
                    }
                }                
            }
        ?>
    </table>
    <div class="writeBtn">
        <p>
            <a href="create.php"\>
                <input type="button" value="글쓰기">
            </a>
        </p>        
    </div>
</body>
<!-- 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함. -->
<script src="<?=$search.'?'.filemtime($search)?>"></script>
</html>