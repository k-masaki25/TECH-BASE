<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset ="UTF-8">
<title>フォームからデータを受け取る</title>
</head>
<body>

<h1>フォームデータの送信</h1>
<form action ="" method ="post">
<input type ="text" name ="comment" value ="コメント"><br>
<input type ="submit" value ="submit">
</form>
<br>
<?php
/****************php scripte*************/
?>
<p><?php
date_default_timezone_set('ja');
if(isset($_POST['comment']) && $_POST['comment'] != ''){
$filename = 'mission_1-6_kimura.txt';
$comment = $_POST['comment'];
$fp = fopen($filename, 'a');
fwrite($fp, $comment."\r\n");
fclose($fp);
if($comment == "完成"){
echo "おめでとう！";
}else {
echo "ご入力ありがとうございます<br>".date("Y年m月d日 H時i分s秒")."に".$comment."を受け付けました。";
}
}
?></p>
</body>
</html>