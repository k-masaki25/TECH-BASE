<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$id = 1;	//
$nm = 'Your Favorite';	//適当な名前
$kome = 'Your Favorite';	//適当なコメント
$sql = "update tbtest set name='$nm', comment='$kome' where id = $id";
$result = $pdo -> query($sql);
?>