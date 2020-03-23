<?php
session_start();
require('../config/function.php');

$db = dbConnect();

if(isset($_POST['email'])){
	$stmt = $db->prepare('select * from users where email = ?');
	$stmt->execute([escape($_POST['email'])]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	if($_POST['password'] ==  $user['password']){
		$_SESSION['login_id'] = $user['id'];
		$_SESSION['cart'] = [];
		header('location: ../products/product_list.php');
	}else{
		echo 'メールアドレス又はパスワードが間違っています。';
	}
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Login Page</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
		<h1>ログイン画面</h1>
		<form action="" method="post">
			<p>email:<input type="email" name="email" class="search" required></p>
			<p>password:<input type="password" name="password" class="search" required></p><br>
			<div class="user_btn_div">
				<input type="submit" value="ログイン" class="btn">
			</div>
		</form>
		<div class="user_btn_div">
			<button type="button" class="user_btn" onclick="location.href='user_register.php'">会員登録ページへ</button>
		</div>
		<div class="user_btn_div">
			<button type="button" class="user_btn" onclick="location.href='../products/product_list.php'">商品一覧ページへ</button>
		</div>
	</div>
</body>
</html>
