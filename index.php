<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }

    if(isset($_SESSION['success_txt'])){
        echo "<script>alert('{$_SESSION['success_txt']}')</script>";
        unset($_SESSION['success_txt']);
    }
?>
<?php
    $index_css = "index.css";
    $search_js = "search.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href=<?=$index_css.'?'.filemtime($index_css)?>>
</head>
<body>
    <div class="header">
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>

    <form action="index.php" method="GET" class="form">
        <input type="text" name="keyword" autocomplete="off" placeholder="제목을 입력해주세요." class="title" value="
<?php
    // 검색한 단어가 input에 남아있도록 처리
    if(isset($_GET['keyword'])){
        echo $_GET['keyword'];
    }
?>">
        <input type="button" value="검색" class="submitBtn">
    </form>

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

            // 테이블 로우 생성 함수
            function makeTableRow($sql){
                $result = mysqli_query($GLOBALS['conn'] , $sql);

                $resultNumRows = mysqli_num_rows($result); 
                                
                $resultAllRows = mysqli_fetch_all($result);
        
                if($resultNumRows !== 0){
                    for($i=0; $i<$resultNumRows; $i++){
                        $id = $resultAllRows[$i][0];
                        $title = htmlspecialchars($resultAllRows[$i][1]); 
                        $created = $resultAllRows[$i][3];
                        // 제목을 검색하고 게시글을 클릭했을 때,
                        // 검색했던 페이지로 이동할 수 있도록 처리하기 위해서
                        // url에 키워드 매개변수 추가 
                        if(isset($_GET['keyword'])){
                            $html = 
                            "
                            <tr>
                            <td>$id</td>
                            <td>
                            <a href=\"content.php?id={$id}&keyword={$_GET['keyword']}\">{$title}</a>
                            </td>
                            <td>$created</td>
                            </tr>
                            ";
                        }
                        else{
                            $html = 
                            "
                            <tr>
                            <td>$id</td>
                            <td>
                            <a href=\"content.php?id={$id}\">{$title}</a>
                            </td>
                            <td>$created</td>
                            </tr>
                            ";
                        }
                        echo $html;
                    }
                }
            }

            if(isset($_GET['keyword'])){
                $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']); 

                $sql = "SELECT * FROM topic WHERE title LIKE '%{$filtered_keyword}%'";

                makeTableRow($sql);
            }
            else{
                $sql = "SELECT * FROM topic";

                makeTableRow($sql);
            }
        ?>
    </table>
    <div class="button">
        <p class="moveBtn">
            <a href="index.php">
                <!-- 제목을 검색했을 때 목록으로 이동하는 버튼이 생성되도록 처리 -->
                <input type="<?php
                    if(isset($_GET['keyword'])){
                        echo "button";
                    }
                    else{
                        echo "hidden";
                    }
                ?>" value="목록">
            </a>
        </p>   
        <p>
            <!-- 제목을 검색한 뒤 글쓰기 버튼을 누르고 취소했을 때 
            검색했던 페이지로 이동할 수 있도록 처리 -->
            <a href="<?php
                if(isset($_GET['keyword'])){
                    echo "create.php?keyword={$_GET['keyword']}";
                }
                else{
                    echo "create.php";
                }
            ?>">
                <input type="button" value="글쓰기">
            </a>
        </p>        
    </div>
</body>
<!-- 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함. -->
<script src="<?=$search_js.'?'.filemtime($search_js)?>"></script>
</html>