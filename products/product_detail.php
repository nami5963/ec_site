<?php
session_start();
require('../config/function.php');

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

$db = dbConnect();

$stmt = $db->prepare('select * from products where id = ?');
$stmt->execute([$_GET['product_id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['addCart'])){
	$_SESSION['cart'][$product['name']] = ['product_id' => $product['id'], 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $_POST['quantity']];
	header('location: ../cart/cart.php');
}

$com_stmt = $db->prepare('select * from comments where product_id = ?');
$com_stmt->execute([$_GET['product_id']]);
$comments = $com_stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['delete'])){
	$del_stmt = $db->prepare('delete from comments where comment_id = ?');
	$del_stmt->execute([$_POST['comment_id']]);
	header("location: product_detail.php?product_id={$_GET['product_id']}");
}

if(isset($_POST['fav'])){
	$fav_sql = 'insert into favorites (product_id, user_id) values (?, ?)';
	$fav_stmt = $db->prepare($fav_sql);
	$fav_stmt->bindParam(1, $_GET['product_id'], PDO::PARAM_STR);
	$fav_stmt->bindParam(2, $_SESSION['login_id'], PDO::PARAM_STR);
	$fav_stmt->execute();
	header("location: product_detail.php?product_id={$_GET['product_id']}");
}

if(isset($_POST['dis_fav'])){
        $dis_fav_sql = 'delete from favorites where product_id = ? and user_id = ?';
        $dis_fav_stmt = $db->prepare($dis_fav_sql);
	$dis_fav_stmt->bindParam(1, $_GET['product_id'], PDO::PARAM_STR);
        $dis_fav_stmt->bindParam(2, $_SESSION['login_id'], PDO::PARAM_STR);
        $dis_fav_stmt->execute();
        header("location: product_detail.php?product_id={$_GET['product_id']}");
}


$get_fav_stmt = $db->prepare('select * from favorites where product_id = ?');
$get_fav_stmt->execute([$_GET['product_id']]);
$favs = $get_fav_stmt->fetchAll(PDO::FETCH_ASSOC);

$count = count($favs);

foreach($favs as $key => $fav){
        if(in_array($_SESSION['login_id'], $fav)){
                $fav_exists_check = true;
        }
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Product Detail</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
		<h1><?=$product['name']?>の商品詳細画面です</h1>
		<p><br><img src="<?= $product['image'] ?>" width="300px" height="250px"></p>
		<p>紹介文<br><?= $product['introduction']?></p>
		<p>値段<br><?= $product['price']?>円/個</p>
		<?php if(empty($count)){ ?>
			<p>この商品はまだ「いいね」されていません</p>
		<?php }else{ ?>
			<p>この商品は<?= $count ?>人に「いいね」されています</p>
		<?php } ?>
		<?php if(isset($_SESSION['login_id'])){ ?>
			<?php if($fav_exists_check){ ?>
				<div class="btn_div">
					<form action="" method="post">
						<input type="submit" name="dis_fav" value="「いいね」を取り消す" class="btn">
					</form>
				</div>
			<?php }else{ ?>
				<div class="btn_div">
					<form action="" method="post">
						<input type="submit" name="fav" value="この商品に「いいね」する" class="btn">
					</form>
				</div>
			<?php } ?>
		<?php } ?>
		<?php if(empty($comments)){ ?>
			<p>口コミはありません</p>
		<?php }else{ ?>
			<p>口コミ一覧</p>
			<table border='1'>
				<tr><td class="column">ニックネーム</td><td class="column">コメント</td><td class="column"></td></tr>
				<?php foreach($comments as $key => $value){ ?>
					<tr>
						<td class="column"><?=escape($value['nickname'])?></td>
						<td class="column"><?=escape($value['comment'])?></td>
						<td class="column"><?php if($value['user_id'] == $_SESSION['login_id']){ ?>
							<form action="" method="post">
								<input type="submit" value="削除" name="delete" class="btn">
								<input type="hidden" name="comment_id" value="<?= $value['comment_id'] ?>">
								<input type="hidden" name="counter" value="<?= $counter ?>">
							</form>
						<?php } ?></td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>
		<form action="" method="post">
			<p>購入量：<select name="quantity">
			<?php for($i = 1; $i < 11; $i ++){ ?>
				<option value="<?= $i ?>"><?= $i ?></option>
			<?php } ?>
			</select></p>
			<div class="btn_div">
				<input type="submit" name="addCart" value="カートに入れる" class="btn">
			</div>
		</form>
		<div id="a_div">
		<a href="product_list.php">商品一覧画面に戻る</a>
		<a href="product_comment.php?product_id=<?= $_GET['product_id'] ?>">この商品にコメントする</a>
		</div>
	</div>
</body>
</html>
