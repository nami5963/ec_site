<?php
session_start();
require('../config/function.php');
ini_set('display_errors', "Off");

$db = dbConnect();

if($_GET['search']){
	$sql = "select * from products where name like ?";
	$stmt = $db->prepare($sql);
	$wildcard_str = "%" . escape($_GET['search']) . "%";
	$stmt->bindParam(1, $wildcard_str, PDO::PARAM_STR);
	$stmt->execute();
}else{
	$stmt = $db->prepare('select * from products');
	$stmt->execute();
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_SESSION['login_id']){
	$user_sql = 'select * from users where id = ?';
	$user_stmt = $db->prepare($user_sql);
	$user_stmt->execute([$_SESSION['login_id']]);
	$login_user = $user_stmt->fetch(PDO::FETCH_ASSOC);
}

if(!($_GET['sort']) || $_GET['sort'] == 'new'){
	foreach($products as $key => $value){
		$id[$key] = $value['id'];
	}
	array_multisort($id, SORT_DESC, $products);
}elseif($_GET['sort'] == 'price_up'){
	foreach($products as $key => $value){
		$price[$key] = $value['price'];
	}
	array_multisort($price, SORT_ASC, $products);
}elseif($_GET['sort'] == 'price_down'){
	foreach($products as $key => $value){
		$price[$key] = $value['price'];
	}
	array_multisort($price, SORT_DESC, $products);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Product List</title>
	<link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
	<h1>商品一覧画面</h1>
	<?php if($login_user){ ?>
		<p id="login_user">ログイン中のユーザー：<?= $login_user['name'] ?></p>
	<?php } ?>
	<form action="" method="get" id="form">
		<p><input type="text" name="search" class="search" placeholder="商品名検索"></p>
		<input type="hidden" name="sort" value="<?= $_GET['sort'] ?>">
	</form>
	<form action="" method="get" id="sort">
		<input type="hidden" name="search" value="<?= $_GET['search']?>">
		<p>並び順:
		<select name="sort" onchange="submit(this.form)">
			<option value="new">新着順</a></option>
			<option value="price_up"<?php if($_GET['sort'] == 'price_up'){echo 'selected';} ?>>安い順</option>
			<option value="price_down"<?php if($_GET['sort'] == 'price_down'){echo 'selected';} ?>>高い順</option>
		</select>
	</form>
	<?php if($_GET['search']){ ?>
		<p class="search_result">「<?= escape($_GET['search']) ?>」の検索結果</p>
	<?php } ?>
	<?php if(!($products)){ ?>
		<p class="search_result_none">商品がありません</p>
	<?php }else{ ?>
		<table border='1'>
			<tr><td class="column">Name</td><td class="column">image</td><td class="column">introduction</td><td class="column">price</td></tr>
 			<?php foreach($products as $product){ ?>
 				<tr>
 					<td class="column"><?=$product['name']?></td>
 					<td class="column"><img src="<?= $product['image'] ?>" width="100px" height="85px"></td>
					<td class="column"><?=$product['introduction']?></td>
					<td class="column"><?=$product['price']?></td>
					<td>
						<div class="btn_div"><button type="button" class="btn" onclick="location.href='product_detail.php?product_id=<?= $product['id'] ?>'">詳細</button></div>
 					</td>
 				</tr>
			<?php } ?>
		</table>
	<?php } ?>
	<div id="a_div">
		<a href="../cart/cart.php">カート画面へ</a>
		<a href="product_ranking.php">売れ筋ランキングへ</a>
		<?php if($_SESSION['login_id']){ ?>
			<a href="../users/logout.php">ログアウト</a>
		<?php }else{ ?>
			<a href="../users/login.php">ログイン</a>
		<?php } ?>
	</div>
	</div>
</body>
</html>
