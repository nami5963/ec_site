<?php

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Order Complete</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
	<h1>購入が完了しました</h1>
	<a href="../products/product_list.php">商品一覧に戻る</a>
	</div>
</body>
</html>
