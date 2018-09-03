<!DOCTYPE html>
<html lang = "ja">

<head>
	<meta charset ="UTF-8">
	<title>フォームからデータを受け取る</title>
</head>

<body>

	<h1>フォームデータの送信</h1>
	<form action ="" method ="post">
		<input type ="text" name ="name" placeholder ="名前"><br>
		<input type ="text" name ="comment" placeholder ="コメント"><br>
		<input type ="submit" value ="submit">
	</form>
<br>
<?php
/****************php scripte*************/
/*textファイルへ書き込み*/
?>
<p><?php
date_default_timezone_set('ja');
if(isset($_POST['name']) && isset($_POST['comment']) && $_POST['name'] !='' && $_POST['comment'] != ''){
	$filename = 'mission_2-2_YourName.txt';
	$name = $_POST['name'];
	$comment = $_POST['comment'];
if(file_exists($filename)){
	$n_submit = count(file($filename)) + 1;
	}else{
		$n_submit = 1;
	}
$fp = fopen($filename, 'a');
fwrite($fp, $n_submit. "<>". $name. "<>" . $comment. "<>". date("Y/m/d/ H:i:s") . "\r\n");
fclose($fp);
}
/****************php scripte*************/
?>
</p>
<br>
<p>
<?php
/****************php scripte*************/

$filename = 'mission_2-2_YourName.txt';
if(file_exists($filename)){
	$file = new SplFileObject($filename);
	$file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

	foreach ($file as $line) {
		if ($line === false) continue;
		$submit_unit = explode("<>", $line);	//explodeはarrayで格納されるためfor文で1行で表示できるようする。
		foreach($submit_unit as $value){
			echo " $value";
		}
		echo nl2br(PHP_EOL);
	}
}else{
	echo "file not found";
}

/****************php scripte*************/
?>
	</p>
</body>
</html>
