<?php
include 'db_conn_web.php';

function getPassword($connection) { //현재 비밀번호 가져오기 및 표시
  $pwsql = "SELECT pw FROM password ORDER BY date DESC LIMIT 1";
  $stmt = $connection->query($pwsql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['pw'];
}

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
  if (isset($_POST['P_RESET'])) {
    // 4자리 숫자 비밀번호 생성
    $password = strval(rand(1000, 9999));

    // 비밀번호와 시간을 데이터베이스에 입력
    $insertSql = "INSERT INTO password (pw) VALUES (:pw)";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bindParam(':pw', $password);
    $insertStmt->execute();
  
    $password = getPassword($connection);
  } else if (isset($_POST['LOCK_ON'])) {
    // errorcode 컬럼에 0 삽입(비밀번호 비교활성화)
    $errorCode = 0;
    $insertSql = "INSERT INTO error (errorcode) VALUES (:errorcode)";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bindParam(':errorcode', $errorCode);
    $insertStmt->execute();
    echo "<script>alert('비밀번호 입력이 활성화되었습니다.');</script>";
  }
  
  $password = getPassword($connection);

  $dataSql = "SELECT word, date FROM textms ORDER BY date DESC LIMIT 10";
  $dataStmt = $connection->query($dataSql);
  $dataResults = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function redirectToPage(data) {
            window.location.href = data+".php";
        }
    </script>
</head>
<body>
  <form method="POST">
    <h2>현재 비밀번호 : <?php echo $password; ?></h2>

    <p> -------------------------------------------------------- </p>
    
    <p style="font-size:20px"><strong>작동 내역</strong></p>
    <table>
      <?php foreach ($dataResults as $row): ?>
        <tr>
          <td><?php echo date('m.d H:i:s', strtotime($row['date'])); ?></td>
          <?php if ($row['word'] === "사진을 촬영했습니다." 
                    || $row['word'] === "비밀번호가 비활성화 되었습니다."): ?>
          <td style="color: red;"><?php echo $row['word']; ?></td>
          <?php else: ?>
          <td><?php echo $row['word']; ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <br>

    <style>
    .button-container {
        display: flex;
        justify-content: center;
    }

    .button-container button {
      width: 300px;
      padding: 10px;
      height: 38px;
    }
</style>

    
    <div class="button-container">
      <button type="submit" name="P_RESET">비밀번호 변경</button>
      <button type="submit" name="LOCK_ON">잠금 해제</button>
      <button type="button" onclick="redirectToPage('image')">침입자 확인</button><br><br><br>
    </div>

    <a href="logout.php">로그아웃</a>
  </form>  
</body>
</html>

<?php
}else {
      header("Location: index.php");
      exit();
}
?>