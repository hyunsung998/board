<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>게시판</h1>
        <p>자유롭게 게시글을 작성해주세요.</p>
    </div>
    <table>
        <tr class="column">
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
                $title = $row['title'];
                $created = $row['created'];
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
    <h3><a href="create.php">글쓰기</a></h3>
</body>
</html>