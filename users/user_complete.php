<?php

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

?>

<!DOCTYPE html>
<html>
<body>
	<h1>登録完了しました</h1>
	<a href="../products/product_list.php">商品一覧画面へ</a>
	<a href="login.php">ログイン画面へ</a>
</body>
</html>
