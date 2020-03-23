<?php
session_start();
require('../config/function.php');

if(isset($_POST['regi'])){
	if(mb_strlen($_POST['name']) > 40){
		echo '名前は40文字までです';
	}elseif(mb_strlen($_POST['address']) > 255){
		echo '住所は255文字までです';
	}elseif(mb_strlen($_POST['email']) > 255){
		echo 'emailは255文字までです';
	}elseif(mb_strlen($_POST['password']) > 20){
		echo 'パスワードは20文字までです';
	}elseif(mb_strlen($_POST['CCNumber']) != 16){
		echo 'クレジットカード番号は16桁です';
	}else{
		$_SESSION['user_regi']['name'] = escape($_POST['name']);
		$_SESSION['user_regi']['address'] = escape($_POST['address']);
		$_SESSION['user_regi']['email'] = escape($_POST['email']);
		$_SESSION['user_regi']['password'] = escape($_POST['password']);
		$_SESSION['user_regi']['CCNumber'] = escape($_POST['CCNumber']);
		header('location: user_confirm.php');
	}
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>User Register</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
		<h1>ユーザー登録</h1>
		<form action="" method="post">
			<p>名前:<input type="text" class="search" name="name" value="<?php if(!empty($_POST['name'])){echo escape($_POST['name']);} ?>" required></p>
			<p>住所:<input type="text" class="search" name="address" value="<?php if(!empty($_POST['address'])){echo escape($_POST['address']);} ?>" required></p>
			<p>メールアドレス:<input type="email" class="search" name="email" value="<?php if(!empty($_POST['email'])){echo escape($_POST['email']);} ?>" required></p>
			<p>パスワード:<input type="password" class="search" name="password" required></p>
			<p>クレジットカード番号:<input type="text" class="search" name="CCNumber" value="<?php if(!empty($_POST['CCNumber'])){echo escape($_POST['CCNumber']);} ?>" required></p>
			<div class="user_btn_div">
				<input type="submit" class="btn" name="regi" value="送信">
			</div>
		</form>
		<div class="user_btn_div">
			<button type="button" class="user_btn" onclick="location.href='login.php'">ログインページへ</button>
		</div>
		<div class="user_btn_div">
			<button type="button" class="user_btn" onclick="location.href='../products/product_list.php'">商品一覧へ</button>
		</div>
	</div>
</body>
</html>
