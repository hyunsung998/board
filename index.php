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
    <div class="index_login_wrap">
        <a href="login.php">
            <button class="index_login_btn">로그인</button>
        </a>
    </div>

    <div class="header">
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>

    <form action="index.php" method="GET" class="form">
        <div class="form-group">
        <input type="text" name="keyword" autocomplete="off" placeholder="제목을 입력해주세요." class="title form-control"
        value="<?=(isset($_GET['keyword'])) ? $_GET['keyword'] : "" ?>">
        <!-- 삼항연산자 사용하여 input에 검색어 표시 -->
        </div>
        <input type="button" value="검색" class="submitBtn btn btn-default">
    </form>

    <table class="test table table-condensed">
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

            // function use(closures);
            // 클로저는 익명함수입니다. 외부 함수의 문맥에 접근할 수 있습니다.
            // use 키워드로 외부변수인 $conn을 $makeTableRow 변수의 상태로 등록.
            // $GLOBALS 대신 클로저 익명함수를 사용해서 할 수 있다.
            // 하지만 익명함수는 '속도'에 영향을 줄 수 있어서 많이 사용 x
            // 예시
            // $makeTableRow = function ($sql) use($conn){
            //     $result = mysqli_query($conn , $sql);
            // };
            // 함수를 호출할때 함수명 앞에 $ 붙여야함.



            // 함수를 만들 때 외부변수와 연관성을 줄이는게 좋으며,
            // makeTableRow의 함수 이름처럼 함수내의 코드가
            // 테이블의 행을 그리는 코드로만 구성되어 있는게 좋다.
            function makeTableRow($result_rows){
                $result_rows_count = count($result_rows);

                if($result_rows_count !== 0){
                    for($i=0; $i<$result_rows_count; $i++){
                        $board_id = $i+1;
                        $db_id = $result_rows[$i][0];
                        $title = htmlspecialchars($result_rows[$i][1]); 
                        $created = $result_rows[$i][3];
                        // 제목을 검색하고 게시글을 클릭했을 때,
                        // 검색했던 페이지로 이동할 수 있도록 처리하기 위해서
                        // url에 키워드 매개변수 추가 
                        if(isset($_GET['keyword'])){
                            $html = 
                            "
                            <tr>
                            <td>{$board_id}</td>
                            <td>
                            <a href=\"content.php?id={$db_id}&keyword={$_GET['keyword']}\">{$title}</a>
                            </td>
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
                            <td>{$created}</td>
                            </tr>
                            ";
                        }
                        echo $html;
                    }
                }
            }

            if(isset($_GET['keyword'])){
                $filtered_keyword = mysqli_real_escape_string($conn , $_GET['keyword']); 

                // LIKE구문
                // 쿼리문 WHERE절에 주로 사용되며 부분적으로 일치하는 칼럼을 찾을때 사용됩니다.
                // SELECT * FROM [테이블명] WHERE LIKE [조건]
                $sql = "SELECT * FROM topic WHERE title LIKE '%{$filtered_keyword}%' ORDER BY created DESC";

                $result = mysqli_query($conn , $sql);
                                
                $result_rows = mysqli_fetch_all($result);

                makeTableRow($result_rows);
            }
            else{
                $sql = "SELECT * FROM topic ORDER BY created DESC";

                $result = mysqli_query($conn , $sql); 
                                
                $result_rows = mysqli_fetch_all($result);

                makeTableRow($result_rows);
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
                ?>" value="목록" class="listBtn btn btn-default">
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
                <input type="button" value="글쓰기" class="writeBtn btn btn-default">
            </a>
        </p>        
    </div>
</body>
<!-- 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함. -->
<script src=<?="./asset/JS/search.js?".filemtime($search_js)?>></script>
</html>