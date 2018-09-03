<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$id = 2;
$sql = "delete from tbtest where id = $id";
$result = $pdo -> query($sql);
?>