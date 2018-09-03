<?php
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

//3-3
$sql = 'SHOW TABLES';
$result = $pdo -> query($sql);
foreach($result as $row){
    echo $row[0];
    echo '<br>';
}
echo "<hr>";
?>