<?php
session_start();
require('../config/function.php');

$db = dbConnect();

$stmt = $db->prepare('select * from order_details');
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$array = [];
foreach($results as $value){
	if(empty($array[$value['product_id']])){
		$array[$value['product_id']] = $value['quantity'];
	}else{
		$array[$value['product_id']] += $value['quantity'];
	}
}

arsort($array);

foreach($array as $key =>$product){
	$ranking_stmt = $db->prepare('select * from products where id = ?');
	$ranking_stmt->execute([$key]);
	$products[] = $ranking_stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Product Ranking</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
        <h1>売れ筋ランキング</h1>
        <table border='1'>
                <tr><td></td><td class="column">Name</td><td class="column">image</td><td class="column">introduction</td><td class="column">price</td></tr>
                <?php for($i = 0; $i < 5; $i ++){ ?>
			<tr>
				<td class="column"><?= $i+1 ?>位</td>
                                <td class="column"><?= $products[$i]['name']?></td>
                                <td class="column"><img src="<?= $products[$i]['image'] ?>" width="100px" height="75px"></td>
                                <td class="column"><?= $products[$i]['introduction']?></td>
                                <td class="column"><?= $products[$i]['price']?></td>
                                <td>
					<div class="btn_div"><button type="button" class="btn" onclick="location.href='product_detail.php?product_id=<?= $product['id'] ?>'">詳細</button></div>
                                </td>
                        </tr>
                <?php } ?>
        </table>
	<div id="a_div"><a href="product_list.php">商品一覧画面へ</a></div>
	</div>
</body>
</html>
