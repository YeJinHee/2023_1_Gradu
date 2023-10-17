<!DOCTYPE html>
<html>
<head>
    <title>login</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="login.php" method="post"> 
        <h2>로그인</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        
        <input type="text" name="uname" placeholder="아이디"><br>
        <input type="password" name="password" placeholder="비밀번호"><br>
    
        <button type="submit">로그인</button>
        <a href="signup.php" class="ca">아직 계정이 없으신가요?</a>
    </form>
</body>
</html>
