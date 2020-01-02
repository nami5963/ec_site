<?php
session_start();

unset($_SESSION['login_id']);
unset($_SESSION['cart']);

header('location: login.php');

?>
