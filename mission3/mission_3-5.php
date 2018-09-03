<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$sql = $pdo -> prepare("INSERT INTO tbtest (id, name, comment) VALUES ('2', :name, :comment)");
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
$name = 'kimura4';	//好きな名前を入力
$comment = 'test444';	//好きなコメントを入力
$sql -> execute();
?>