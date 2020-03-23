<?php
session_start();
require '../config/function.php';

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

$db = dbConnect();

if(isset($_POST['login'])){
	$login_stmt = $db->prepare("select * from users where email = ?");
        $login_stmt->execute([escape($_POST['email'])]);
        $result = $login_stmt->fetch(PDO::FETCH_ASSOC);
        if(escape($_POST['password']) == $result['password']){
                $_SESSION['login_id'] = $result['id'];
                header("location: product_comment.php?product_id={$_GET['product_id']}");
	}else{
		echo '入力情報に誤りがあります。';
	}
}

if(isset($_POST['submit'])){
	try{
		$db->beginTransaction();

		$sql = "insert into comments (comment_id, product_id, user_id, nickname, comment) values (null, ?, ?, ?, ?)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_GET['product_id'], PDO::PARAM_STR);
		$stmt->bindParam(2, $_SESSION['login_id'], PDO::PARAM_STR);
		$stmt->bindParam(3, escape($_POST['nickname']), PDO::PARAM_STR);
		$stmt->bindParam(4, escape($_POST['comment']), PDO::PARAM_STR);

		$stmt->execute();

		$db->commit();
		header("location: product_detail.php?product_id={$_GET['product_id']}");
	}catch(PDOException $e){
		$db->rollback();
		echo $e->getMessage();
		exit();
	}
}

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title>Product Comment</title>
        <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
		<?php if($_SESSION['login_id'] == true){ ?>
        		<h1>コメント登録</h1>
        		<form action="" method="POST">
				<p>ニックネーム<br><input type="text" name="nickname" class="search" required></p>
				<p>コメント<br><textarea name="comment" rows="5" cols="50" class="comment_box" required></textarea></p>
            			<div class="user_btn_div">
					<input type="submit" value="送信" name="submit" class="btn">
				</div>
        		</form>
			<div class="user_btn_div">
				<button type="button" class="user_btn" onclick="location.href='product_detail.php?product_id=<?= $_GET['product_id'] ?>'">戻る</button>
			</div>
		<?php }else{ ?>
			<p>コメントを登録するにはログインしている必要があります</p>
			<form action="" method="POST">
				<p>email:<input type="email" name="email" class="search" value="<?php if (isset($_POST['email'])) { echo escape($_POST['email']); }?>" requiredi></p>
				<p>password:<input type="password" name="password" class="search" required></p>
				<div class="user_btn_div">
					<input type="submit" value="ログイン" name="login" class="btn">
				</div>
			</form>
			<div class="user_btn_div">
				<button type="button" class="user_btn" onclick="location.href='../users/user_register.php'">会員登録ページへ</button>
			</div>
			<div class="user_btn_div">
				<button type="button" class="user_btn" onclick="location.href='product_detail.php?product_id=<?= $_GET['product_id'] ?>'">戻る</button>
			</div>
		<?php } ?>
	</div>
</body>
</html>
