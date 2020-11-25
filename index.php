<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div>
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
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

            while($row = mysqli_fetch_array($result)){
                $id = $row['id'];
                $escaped_title = htmlspecialchars($row['title']); 
                $created = $row['created'];
        ?>

         <tr>
            <td><?=$id?></td>
            <td>
                <?php
                    echo "<a href=\"content.php?id={$id}\">{$escaped_title}</a>"
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
</html>