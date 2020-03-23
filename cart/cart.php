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
if($_SESSION['cart']){
	foreach($_SESSION['cart'] as $product){
		$total += $product['price'] * $product['quantity'];
	}
}


?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Cart Page</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
	<?php if(!empty($_SESSION['cart'])){ ?>
		<h1>カート画面</h1>
		<table border='1'>
			<tr><td class="column">Name</td><td class="column">price</td><td class="column">quantity</td><td class="column"></td><td class="column"></td></tr>
			<?php foreach($_SESSION['cart'] as $product){ ?>
			<tr>
				<td class="column"><?=$product['name']?></td>
				<td class="column"><?=$product['price']?></td>
				<td class="column"><?=$product['quantity']?></td>
				<?php if($_SESSION['cart'][$product['name']]['quantity'] < 100){ ?>
					<td class="column"><form action="" method="post">
						<input type="submit" name="plus" value="+" class="btn">
						<input type="hidden" name="+" value="<?= $product['name'] ?>">
					</form>
				<?php } ?>
				<?php if($_SESSION['cart'][$product['name']]['quantity'] > 1){ ?> 
					<form action="" method="post">
                                        	<input type="submit" name="minus" value="-" class="btn">
                                        	<input type="hidden" name="-" value="<?= $product['name'] ?>">
                                	</form></td>
				<?php } ?>
				<td class="column"><form action="" method="post">
					<input type="submit" value="削除" name="delete" class="btn">
					<input type="hidden" name="name" value="<?= $product['name'] ?>">
				</form></td>
			</tr>
			<?php } ?>
			</table>
		<p>合計金額：<?= $total ?> 円</p>
		<div class="a_div">
			<a href="../orders/order_confirm.php">購入手続きに進む</a>
			<a href="../products/product_list.php">商品一覧に戻る</a>
		</div>
	<?php }else{ ?>
		<p>カートの中に商品がありません</p>
		<div class="a_div">
			<a href="../products/product_list.php">商品一覧に戻る</a>
		</div>
	<?php } ?>
	</div>
</body>
</html>
