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
		<input type ="submit" value ="submit"><br>
	</form>
<br>
	<form action ="" method ="post">
		<input type ="text" name ="n_delete" placeholder ="削除対象番号"><br>
		<input type ="submit" value ="delete"><br>
	</form>
<?php
/****************php scripte*************/
/*textファイルへ書き込み*/
?>
<p>
<?php
date_default_timezone_set('ja');
if(isset($_POST['name']) && isset($_POST['comment']) && $_POST['name'] !='' && $_POST['comment'] != ''){

	/*投稿数を数える。*/
	$filename1 = 'mission_2-3_post_count.txt';
	if(file_exists($filename1)){
	$line = file($filename1);
	$submit_unit = explode("<>", $line[0]);


		$c_submit = intval($submit_unit[1]) + 1;
	}else{
			$c_submit = 1;
	}
	$fp1 = fopen($filename1, 'w+');
	fwrite($fp1, "投稿数は、". "<>". $c_submit. "<>". date("Y/m/d/ H:i:s"));
	fclose($fp1);

	/*投稿されたコメントに番号を付けて、txtに書き出し。*/
	$filename = 'mission_2-3_kimura.txt';
	$name = $_POST['name'];
	$comment = $_POST['comment'];

	$fp = fopen($filename, 'a');
	fwrite($fp, $c_submit. "<>". $name. "<>" . $comment. "<>". date("Y/m/d/ H:i:s") . nl2br(PHP_EOL));
	fclose($fp);
}
/*入力された数字の行の投稿を削除*/

if(isset($_POST['n_delete']) && $_POST['n_delete'] != ''){//if(is_int($_POST['n_delete'])){	//文字列が数値かどうか
	rename('mission_2-3_kimura.txt', 'before_mission_2-3_kimura.txt');
	$filename1 = 'before_mission_2-3_kimura.txt';
	$filename2 = 'mission_2-3_kimura.txt';
	$fp1 = fopen($filename1, r);
	$fp2 = fopen($filename2, a);
	while(($line = fgets($fp1)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $_POST['n_delete'])continue;
		fwrite($fp2, $line);
	}
	fclose($fp1);
	fclose($fp2);
	unlink($filename1);
}
/***************************************/
?>

</p>
<br>
<p>

<?php
/****************php scripte*************/

$filename = 'mission_2-3_kimura.txt';
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
