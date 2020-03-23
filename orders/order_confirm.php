<?php
session_start();
require '../config/function.php';
date_default_timezone_set('Asia/Tokyo');

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

$db = dbConnect();

if(isset($_SESSION['login_id'])){
	$userStmt = $db->prepare('select * from users where id = ?');
	$userStmt->execute([$_SESSION['login_id']]);
	$user = $userStmt->fetch(PDO::FETCH_ASSOC);
}

$total = 0;
foreach($_SESSION['cart'] as $product){
	$total += $product['price'] * $product['quantity'];
}

$datetime = date("Y/m/d H:i:s");

if(isset($_GET['buy'])){
	try{
		$db->beginTransaction();
		$sql = 'insert into orders (order_id, user_id, total, address, CCNumber, date) values (NULL, ?, ?, ?, ?, ?)';
		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $user['id'], PDO::PARAM_INT);
		$stmt->bindParam(2, $total, PDO::PARAM_INT);
		$stmt->bindParam(3, $user['address'], PDO::PARAM_STR);
		$stmt->bindParam(4, $user['CCNumber'], PDO::PARAM_STR);
		$stmt->bindParam(5, $datetime, PDO::PARAM_STR);
		$stmt->execute();

		$query = 'select max(order_id) from orders';
		$result = $db->query($query);
		$order_detail_id = $result->fetchColumn();

		foreach($_SESSION['cart'] as $product){
			$subtotal = $product['price'] * $product['quantity'];
			$order_detail_sql = 'insert into order_details (order_id, product_id, quantity, subtotal) values (?, ?, ?, ?)';
			$order_detail_stmt = $db->prepare($order_detail_sql);
			$order_detail_stmt->bindParam(1, $order_detail_id, PDO::PARAM_INT);
			$order_detail_stmt->bindParam(2, $product['product_id'], PDO::PARAM_INT);
			$order_detail_stmt->bindParam(3, $product['quantity'], PDO::PARAM_INT);
			$order_detail_stmt->bindParam(4, $subtotal, PDO::PARAM_INT);
			$order_detail_stmt->execute();
		}

		$db->commit();
		$_SESSION['cart'] = [];
		header('location: order_complete.php');
	}catch(PDOException $e){
		$db->rollback();
		echo $e->getMessage();
	}
}

if(isset($_POST['login'])){
	$loginStmt = $db->prepare('select * from users where email = ?');
	$loginStmt->execute([$_POST['email']]);
	$loginUser = $loginStmt->fetch(PDO::FETCH_ASSOC);
	if($loginUser['password'] === $_POST['password']){
		$_SESSION['login_id'] = $loginUser['id'];
		header('location: order_confirm.php');
	}else{
		echo 'メールアドレス又はパスワードが間違っています。';
       	}
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Order Confirm</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
	<?php if($_SESSION['login_id']){ ?>
	<h1>購入する商品</h1>
	<?php foreach($_SESSION['cart'] as $product){ ?>
		<p><?= $product['name'] . ':' . $product['quantity'] . '個->' . $product['price'] * $product['quantity'] . '円' ?></p>
	<?php } ?>
	<p>-----------------------------------------</p>
	<p>合計金額：<?= $total ?>円</p>
	<h1>お客様情報</h1>
	<p>名前：<?= $user['name'] ?></p>
	<p>住所：<?= $user['address'] ?></p>
	<p>メールアドレス：<?= $user['email'] ?></p>
	<p>クレジットカード番号：<?= $user['CCNumber'] ?></p>
	<form action="" method="get">
		<div class="user_btn_div"><input type="submit" value="購入する" name="buy" class="btn"></div>
	</form>
	<div class="user_btn_div"><button type="button" class="user_btn" onclick="location.href='../products/product_list.php'">商品一覧ページへ</button></div>
	<?php }else{ ?>
	<h1>購入するにはログインする必要があります</h1>
	<form action="" method="post">
		<p>email:<input type="email" name="email" class="search" required></p>
		<p>password:<input type="password" name="password" class="search" required></p><br>
		<div class="user_btn_div"><input type="submit" name="login" value="ログイン" class="btn"></div>
		<div class="user_btn_div"><button type="button" class="user_btn" onclick="location.href='user_register.php'">会員登録ページへ</button></div>
	</form>
	<?php } ?>
	</div>
</body>
</html>
