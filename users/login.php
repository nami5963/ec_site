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
		<p>email:<input type="email" name="email" required></p>
		<p>password:<input type="password" name="password" required></p><br>
		<input type="submit" value="ログイン">
		<a href="user_register.php"><input type="button" value="会員登録ページへ"></a>
		<a href="../products/product_list.php"><input type="button" value="商品一覧へ"></a>
	</form>
	</div>
</body>
</html>
