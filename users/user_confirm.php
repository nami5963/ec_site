<?php
session_start();
require('../config/function.php');

if(!($_SERVER["HTTP_REFERER"])){
	header('location: ../index.php');
}

$db = dbConnect();

if(isset($_POST['send'])){

	try{
		$db->beginTransaction();

		$sql = "INSERT INTO users(id, name, address, email, password, CCNumber)VALUES(NULL, :userName, :userAddress, :userEmail, :userPassword, :userCCNumber)";

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':userName', $_SESSION['user_regi']['name'], PDO::PARAM_STR);
		$stmt->bindParam(':userAddress', $_SESSION['user_regi']['address'], PDO::PARAM_STR);
		$stmt->bindParam(':userEmail', $_SESSION['user_regi']['email'], PDO::PARAM_STR);
		$stmt->bindParam(':userPassword', $_SESSION['user_regi']['password'], PDO::PARAM_STR);
		$stmt->bindParam(':userCCNumber', $_SESSION['user_regi']['CCNumber'], PDO::PARAM_STR);

		$stmt->execute();

		$db->commit();
		unset($_SESSION['user_regi']);
		header('location: user_complete.php');
	}catch(Exception $e){
		$db->rollback();
		echo $e->getMessage();
	}
}	

?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
       	<title>Register Confirm</title>
	<link rel="stylesheet" href="../css/styles.css">
</head>
<body>
	<div id="container">
		<h1>登録内容確認</h1>
		<p>名前<br>
			<?php echo $_SESSION['user_regi']['name']; ?>
		</p>
		<p>住所<br>
			<?php echo $_SESSION['user_regi']['address']; ?>
		</p>
		<p>メールアドレス<br>
			<?php echo $_SESSION['user_regi']['email']; ?>
		</p>
		<p>パスワード<br>
			<?php echo $_SESSION['user_regi']['password']; ?>
		</p>
		<p>クレジットカード番号<br>
			<?php echo $_SESSION['user_regi']['CCNumber']; ?>
		</p>
		<form action="" method="post">
			<input type="submit" name="send" value="送信">
			<a href="javascript:history.back();"><input type="button" value="戻る">
		</form>
	</div>
</body>
</html>
