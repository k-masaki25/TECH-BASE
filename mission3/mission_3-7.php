<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$id = 1;
$nm = 'kimura2';
$kome = 'test222';
$sql = "update tbtest set name='$nm', comment='$kome' where id = $id";
$result = $pdo -> query($sql);
?>