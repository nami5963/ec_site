<?php

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

?>
<!DOCTYPE html>
<html>
<body>
	<h1>購入が完了しました</h1>
	<a href="../products/product_list.php">商品一覧に戻る</a>
</body>
</html>
