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
		<div class="user_btn_div"><button type="button" class="user_btn" onclick="location.href='../products/product_list.php'">商品一覧ページへ</button></div>
	</div>
</body>
</html>
