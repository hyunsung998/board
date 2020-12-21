<?php
    session_start();

    if(isset($_SESSION['error_txt'])){
        echo "<script>alert('{$_SESSION['error_txt']}')</script>";
        unset($_SESSION['error_txt']);
    }
?>
<?php
    $index_css = "./asset/CSS/index.css";
    $search_js = "./asset/JS/search.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href=<?="./asset/CSS/index.css?".filemtime($index_css)?>>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <!-- 로그인 -->
    <?php
        if(!isset($_SESSION['username'])){
    ?>
        <div class="index_login_wrap">
            <a href="login.php">
                <button class="index_login_btn">로그인</button>
            </a>
            <a href="join.php">
                <button class="index_join_btn">회원가입</button>
            </a>
        </div>
    <?php
        }
        else{
    ?>
        <div class="index_login_wrap">
            <p><?=$_SESSION['username']?>님 환영합니다.</p>
            <a href="logout.php">로그아웃</a>
        </div>
    <?php
        }
    ?>

    <div class="header">
        <a href="index.php"><h1>게시판</h1></a>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>

    <!-- 검색창 -->
    <form action="index.php" method="GET" id="search_form">
        <input type="text" name="keyword" autocomplete="off" id="title"
        value="<?=(isset($_GET['keyword'])) ? $_GET['keyword'] : "" ?>">
        <input type="button" value="검색" id="submitBtn">
    </form>

    <!-- 테이블 -->
    <table class="table table-condensed">
        <tr>
            <td>No.</td>
            <td>제목</td>
            <td>작성자</td>
            <td>등록일</td>
        </tr>
        <?php
            require __DIR__ . '/vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

            $dotenv->load();
                
            $conn = mysqli_connect("localhost" , $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], "board");
            
            if(isset($_GET['keyword'])){
                $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']);
            }

            function validateUserName($user_id) {
                $sql = "SELECT * FROM users WHERE id='{$user_id}'";

                $result = mysqli_query($GLOBALS['conn'] , $sql);

                $users_row = mysqli_fetch_array($result);

                $username = $users_row[1];

                return $username;
            }

            function makeTableRows($rows){
                $rows_count = count($rows);

                if($rows_count !== 0){
                    for($i=0; $i<$rows_count; $i++){
                        $no = $i+1;
                        $topic_id = $rows[$i][0];
                        $user_id = $rows[$i][1]; 
                        $title = htmlspecialchars($rows[$i][2]); 
                        $created = $rows[$i][4];


                        $username = validateUserName($user_id); 

                        if(isset($_GET['keyword'])){
                            $html = 
                            "
                            <tr>
                            <td>{$no}</td>
                            <td>
                            <a href=\"content.php?id={$topic_id}&keyword={$GLOBALS['filtered_keyword']}\">{$title}</a>
                            </td>
                            <td>{$username}</td>
                            <td>{$created}</td>
                            </tr>
                            ";
                        }
                        else{
                            $html = 
                            "
                            <tr>
                            <td>{$no}</td>
                            <td>
                            <a href=\"content.php?id={$topic_id}\">{$title}</a>
                            </td>
                            <td>{$username}</td>
                            <td>{$created}</td>
                            </tr>
                            ";
                        }
                        echo $html;
                    }
                }
                else{
                    return false;
                }
            }

            if(isset($_GET['keyword'])){
                $sql = "SELECT * FROM topics WHERE title LIKE '%{$filtered_keyword}%' ORDER BY created_at DESC";

                $result = mysqli_query($conn , $sql);
                                
                $rows = mysqli_fetch_all($result);

                makeTableRows($rows);
            }
            else{
                $sql = "SELECT * FROM topics ORDER BY created_at DESC";

                $result = mysqli_query($conn , $sql); 
                                
                $rows = mysqli_fetch_all($result);

                if(count($rows) !== 0){
                    makeTableRows($rows);
                }
            }
        ?>
    </table>

    <!-- 글쓰기 , 목록 버튼 -->
    <div class="button">
            <?php
                if(isset($_GET['keyword'])){
            ?>
                    <a href="index.php">
                        <input type="button" value="목록" class="listBtn btn btn-default">
                    </a>
            <?php
                }
            ?>
            <?php
                if(!isset($_GET['keyword'])){
            ?>
                    <a href="<?php
                        if(isset($_SESSION['username'])){
                            echo "create.php";
                        }
                        else{
                            echo "login.php";
                        }
                    ?>">
                        <input type="button" value="글쓰기" class="writeBtn btn btn-default">
                    </a>
            <?php
                }
            ?>  
    </div>
</body>
<script src=<?="./asset/JS/search.js?".filemtime($search_js)?>></script>
</html>

<!-- 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함. -->

<!-- function use(closures);
클로저는 익명함수입니다. 외부 함수의 문맥에 접근할 수 있습니다.
use 키워드로 외부변수인 $conn을 $makeTableRow 변수의 상태로 등록.
$GLOBALS 대신 클로저 익명함수를 사용해서 할 수 있다.
하지만 익명함수는 '속도'에 영향을 줄 수 있어서 많이 사용 x
예시
$makeTableRow = function ($sql) use($conn){
    $result = mysqli_query($conn , $sql);
};
함수를 호출할때 함수명 앞에 $ 붙여야함. -->

<!-- LIKE구문
쿼리문 WHERE절에 주로 사용되며 부분적으로 일치하는 칼럼을 찾을때 사용됩니다.
SELECT * FROM [테이블명] WHERE LIKE [조건] -->