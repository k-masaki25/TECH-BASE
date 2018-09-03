<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$sql = 'CREATE TABLE tbtest'. '('. 'id INT,'. 'name char(32),'. 'comment TEXT'. ');';
$stmt = $pdo->query($sql);
?>