<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href="index.css">
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
<script src="search.js"></script>
</html>