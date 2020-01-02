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
<body>
        <h1>売れ筋ランキング</h1>
        <table border='1'>
                <tr><td></td><td>Name</td><td>image</td><td>introduction</td><td>price</td></tr>
                <?php for($i = 0; $i < 5; $i ++){ ?>
			<tr>
				<td><?= $i+1 ?>位</td>
                                <td><?= $products[$i]['name']?></td>
                                <td><img src="<?= $products[$i]['image'] ?>" width="100px" height="75px"></td>
                                <td><?= $products[$i]['introduction']?></td>
                                <td><?= $products[$i]['price']?></td>
                                <td>
                                        <a href="product_detail.php?product_id=<?= $products[$i]['id'] ?>"><input type="button" value="詳細"></a>
                                </td>
                        </tr>
                <?php } ?>
        </table>
        <a href="product_list.php">商品一覧画面へ</a>
</body>
</html>
