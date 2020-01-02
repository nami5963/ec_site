<?php
session_start();

//var_dump($_SESSION);

if(isset($_POST['plus'])){
	$_SESSION['cart'][$_POST['+']]['quantity'] += 1;
}

if(isset($_POST['minus'])){
	$_SESSION['cart'][$_POST['-']]['quantity'] -= 1;
}

if(isset($_POST['delete'])){
	unset($_SESSION['cart'][$_POST['name']]);
	$_POST['name'] = array();
}

$total = 0;
if($_SESSION){
	foreach($_SESSION['cart'] as $product){
		$total += $product['price'] * $product['quantity'];
	}
}


?>

<!DOCTYPE html>
<html>
<body>
	<?php if(!empty($_SESSION['cart'])){ ?>
		<h1>カート画面</h1>
		<table border='1'>
			<tr><td>Name</td><td>price</td><td>quantity</td><td></td><td></td></tr>
			<?php foreach($_SESSION['cart'] as $product){ ?>
			<tr>
				<td><?=$product['name']?></td>
				<td><?=$product['price']?></td>
				<td><?=$product['quantity']?></td>
				<?php if($_SESSION['cart'][$product['name']]['quantity'] < 100){ ?>
					<td><form action="" method="post">
						<input type="submit" name="plus" value="+">
						<input type="hidden" name="+" value="<?= $product['name'] ?>">
					</form>
				<?php } ?>
				<?php if($_SESSION['cart'][$product['name']]['quantity'] > 1){ ?> 
					<form action="" method="post">
                                        	<input type="submit" name="minus" value="-">
                                        	<input type="hidden" name="-" value="<?= $product['name'] ?>">
                                	</form></td>
				<?php } ?>
				<td><form action="" method="post">
					<input type="submit" value="削除" name="delete">
					<input type="hidden" name="name" value="<?= $product['name'] ?>">
				</form></td>
			</tr>
			<?php } ?>
		<p>合計金額：<?= $total ?> 円</p>
		<a href="../orders/order_confirm.php">購入手続きに進む</a><br>
		<a href="../products/product_list.php">商品一覧に戻る</a>
	<?php }else{ ?>
		<p>カートの中に商品がありません</p>
		<a href="../products/product_list.php">商品一覧に戻る</a>
	<?php } ?>
</body>
</html>
