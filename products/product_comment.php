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
<body>
    <?php if($_SESSION['login_id'] == true){ ?>
        <h1>コメント登録</h1>
        <form action="" method="POST">
            ニックネーム<br>
            <input type="text" name="nickname" required><br><br>
            コメント<br>
            <textarea name="comment"  rows="5" cols="50" required></textarea><br><br>
            <input type="submit" value="送信" name="submit">
        </form>
        <a href="product_detail.php?product_id=<?= $_GET['product_id'] ?>">戻る</a>
    <?php }else{ ?>
        <p>コメントを登録するにはログインしている必要があります</p>
        <form action="" method="POST">
            email<br>
            <input type="text" name="email" value="<?php if (isset($_POST['email'])) { echo escape($_POST['email']); }?>" required><br><br>
            パスワード<br>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="ログイン" name="login">
        </form>
        <a href="../users/user_register.php">ユーザー新規登録画面へ</a>
        <a href="product_detail.php?product_id=<?= $_GET['product_id'] ?>">戻る</a>
    <?php } ?>
</body>
</html>
