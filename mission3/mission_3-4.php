<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$sql ='SHOW CREATE TABLE tbtest';
$result = $pdo->query($sql);
foreach ($result as $row){
    print_r($row);
}

echo "<hr>";
?>