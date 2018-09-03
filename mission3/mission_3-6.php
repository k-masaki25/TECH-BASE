<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$sql = 'SELECT * FROM tbtest';
$results = $pdo -> query($sql);

foreach($results as $row){
    //$rowの中にはテーブルのカラムが入る
    echo $row['id']. ',';
    echo $row['name']. ',';
    echo $row['comment']. '<br>';
}
?>