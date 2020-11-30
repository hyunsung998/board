<?php
    // 브라우저에서는 한 번 불러온 css,js 파일을 캐시 해두기 때문에 수정을 해도 바로 적용이 안된다.
    // filemtime 함수는 파일 내용이 마지막으로 수정 된 시간을 반환함.
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
        <form action="search.php" method="post" class="form">
            <input type="text" name="title" autocomplete="off" placeholder="검색할 제목을 입력해주세요." class="title">
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
            $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");

            $sql = "SELECT * FROM board";

            $result = mysqli_query($conn , $sql);

            // 테이블 레코드의 총 개수
            $resultNumRows = mysqli_num_rows($result); 
            
            // 테이블 모든 레코드를 배열로 반환
            $resultAllRows = mysqli_fetch_all($result);
            
            // 테이블 레코드의 총 개수만큼 테이블 행 생성
            for($i=0; $i < $resultNumRows; $i++){
                $id = $resultAllRows[$i][0];
                $title = htmlspecialchars($resultAllRows[$i][1]);
                $created = $resultAllRows[$i][3]; 
        ?>

         <tr>
            <td><?=$id?></td>
            <td>
                <?php
                    echo "<a href=\"content.php?id={$id}\">{$title}</a>"
                ?>
            </td>
            <td><?=$created?></td>
         </tr> 

        <?PHP    
            }
        ?>
    </table>
    <div class="writeBtn">
    <p><a href="create.php"><input type="button" value="글쓰기"></a></p>        
    </div>
</body>
<script src=<?=$search.'?'.filemtime($search)?>></script>
</html>