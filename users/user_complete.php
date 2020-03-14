<?php

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Register Complete</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
	<h1>登録完了しました</h1>
	<div id="a_div">
		<a href="../products/product_list.php">商品一覧画面へ</a>
		<a href="login.php">ログイン画面へ</a>
	</div>
	</div>
</body>
</html>
