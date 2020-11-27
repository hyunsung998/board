<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Board</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="headerTitle">
        <h1>검색결과</h1>
    </div>
    <div class="searchCont">
        <form action="search.php" method="post" class="form">
            <input type="text" name="title" autocomplete="off" placeholder="검색할 제목을 입력해주세요." class="title">
            <input type="button" value="검색" class="submitBtn">
        </form>
    </div>
    <table class="table">
        <tr>
            <td>No</td>
            <td>제목</td>
            <td>등록일</td>
        </tr>
    <?php
        $conn = mysqli_connect("localhost" , "root" , "036087" , "board_db");
         
        $filtered_title = mysqli_real_escape_string($conn , $_POST['title']); 

        // DB에 입력된 타이틀과 동일한 데이터가 있는지 확인
        $sql = "SELECT * FROM board WHERE title='{$filtered_title}'";

        $result = mysqli_query($conn , $sql);

        // 입력된 타이틀과 같은 레코드의 총 개수
        $resultNumRows = mysqli_num_rows($result); 
        
        // 레코드를 배열로 반환
        $resultAllRows = mysqli_fetch_all($result);

        // 레코드의 개수가 0이 아닐 경우
        if($resultNumRows !== 0){
            for($i=0; $i<$resultNumRows; $i++){
                $id = $resultAllRows[$i][0];
                $title = htmlspecialchars($resultAllRows[$i][1]); 
                $created = $resultAllRows[$i][3];

                // 함수의 매개변수로 $id,title,created 값을 전달해주지 못했다.

                echo "<script>
                    var tr_elem = document.createElement('tr');
                    var table_elem = document.querySelector('.table');

                    tr_elem.innerHTML =
                    '<td>{$id}</td>' +
                    '<td><a href=content.php?id={$id}>{$title}</a></td>' +
                    '<td>{$created}</td>';
                
                    table_elem.appendChild(tr_elem);
                </script>";
            }
        }
        else{
            echo "<script>alert('검색 결과가 없습니다.');</script>";
        }
    ?>
    </table>
    <div class="writeBtn">
        <a href="index.php"><input type="button" value="게시판으로 이동"></a>     
    </div>
    </body>
    <script src="search.js"></script>
</html>