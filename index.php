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
        if(!isset($_SESSION['user_id'])){
    ?>
        <div class="index_login_wrap">
            <a href="login.php">
                <button class="index_login_btn">로그인</button>
            </a>
        </div>
    <?php
        }
        else{
    ?>
        <div class="index_login_wrap">
            <p><?=$_SESSION['user_id']?>님 환영합니다.</p>
            <a href="logout.php">로그아웃</a>
        </div>
    <?php
        }
    ?>

    <div class="header">
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>

    <!-- 검색창 -->
    <form action="index.php" method="GET" class="form">
        <div class="form-group">
        <input type="text" name="keyword" autocomplete="off" placeholder="제목을 입력해주세요." class="title form-control"
        value="<?=(isset($_GET['keyword'])) ? $_GET['keyword'] : "" ?>">
        <!-- 삼항연산자 사용하여 input에 검색어 표시 -->
        </div>
        <input type="button" value="검색" class="submitBtn btn btn-default">
    </form>

    <!-- 테이블 -->
    <table class="test table table-condensed">
        <tr>
            <td>No</td>
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

            // topic 테이블 필드 중 user_id를 통해서 작성자를 찾는 함수
            function validateUserId($user_id) {
                $sql = "SELECT * FROM users WHERE id='{$user_id}'";

                $result = mysqli_query($GLOBALS['conn'] , $sql);

                $row = mysqli_fetch_array($result);

                return $row[1];
            }

            // 테이블 로우 생성 함수
            function makeTableRow($result_rows){
                $result_rows_count = count($result_rows);

                if($result_rows_count !== 0){
                    for($i=0; $i<$result_rows_count; $i++){
                        $board_id = $i+1;
                        $db_id = $result_rows[$i][0];
                        $title = htmlspecialchars($result_rows[$i][2]); 
                        $created = $result_rows[$i][4];
                        $user_id = $result_rows[$i][1]; 

                        $login_id = validateUserId($user_id); // 함수 실행

                        if(isset($_GET['keyword'])){
                            $html = 
                            "
                            <tr>
                            <td>{$board_id}</td>
                            <td>
                            <a href=\"content.php?id={$db_id}&keyword={$GLOBALS['filtered_keyword']}\">{$title}</a>
                            </td>
                            <td>{$login_id}</td>
                            <td>{$created}</td>
                            </tr>
                            ";
                        }
                        else{
                            $html = 
                            "
                            <tr>
                            <td>{$board_id}</td>
                            <td>
                            <a href=\"content.php?id={$db_id}\">{$title}</a>
                            </td>
                            <td>{$login_id}</td>
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

            // 검색을 했을 때와 안했을 때 테이블 로우를 생성하는 두 가지 경우
            if(isset($_GET['keyword'])){
                // LIKE구문
                // 쿼리문 WHERE절에 주로 사용되며 부분적으로 일치하는 칼럼을 찾을때 사용됩니다.
                // SELECT * FROM [테이블명] WHERE LIKE [조건]
                $sql = "SELECT * FROM topics WHERE title LIKE '%{$filtered_keyword}%' ORDER BY created DESC";

                $result = mysqli_query($conn , $sql);
                                
                $result_rows = mysqli_fetch_all($result);

                makeTableRow($result_rows);
            }
            else{
                $sql = "SELECT * FROM topics ORDER BY created DESC";

                $result = mysqli_query($conn , $sql); 
                                
                $result_rows = mysqli_fetch_all($result);

                if(count($result_rows) !== 0){
                    makeTableRow($result_rows);
                }
            }
        ?>
    </table>
    <div class="button">
        <p class="moveBtn">
            <a href="index.php">
                <!-- 검색했을 때 목록 이동 버튼이 생성되도록 처리 -->
                <input type="<?php
                    if(isset($_GET['keyword'])){
                        echo "button";
                    }
                    else{
                        echo "hidden";
                    }
                ?>" value="목록" class="listBtn btn btn-default">
            </a>
        </p>   
        <p>
            <!-- 로그인 아이디가 있는지 없는지 확인 -->
            <a href="<?php
                if(isset($_SESSION['user_id'])){
                    echo "create.php";
                }
                else{
                    echo "login.php";
                }
            ?>">
            <!-- 검색할 경우 글쓰기 버튼이 없어지도록 -->
                <input type="<?php
                    if(isset($_GET['keyword'])){
                        echo "hidden";
                    }
                    else{
                        echo "button";
                    }
                ?>" value="글쓰기" class="writeBtn btn btn-default">
            </a>
        </p>        
    </div>
</body>
<!-- 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함. -->
<script src=<?="./asset/JS/search.js?".filemtime($search_js)?>></script>
</html>

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