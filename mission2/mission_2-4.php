<!DOCTYPE html>
<html lang = "ja">
<?php
$filename1 = 'mission_2-4_kimura.txt';
$filenameb = 'before_mission_2-4_kimura.txt';
$filename2 = 'mission_2-4_post_count.txt';
$filename3 = 'mission_2-4.php';
?>

<head>
	<meta charset ="UTF-8">
	<title>フォームからデータを受け取る</title>
</head>

<body>

	<h1>フォームデータの送信</h1>
	
    <?php
		$edit_name = Null;
		$edit_comment = Null;
		$n_edit1 = Null;
    	if(isset($_POST['n_edit1']) && $_POST['n_edit1'] != ''){
        	$file = new SplFileObject($filename1);
			$file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
			
			foreach ($file as $line) {
	        	if ($line === false) {
		        	continue;
	        	}else{
		        	$submit_unit = explode("<>", $line);
		        	if($submit_unit[0] === $_POST['n_edit1'] ){		//編集対象番号のコメントがある場合
						$n_edit1 = $submit_unit[0];
						$edit_name = $submit_unit[1];
			        	$edit_comment = $submit_unit[2];
		        	}else{									//編集対象番号のコメントがない場合
			        	continue;
		        	}
	        	}
			}
			
			if($n_edit1 === Null){
				echo '編集対象番号のコメントがありません'. nl2br(PHP_EOL);
			}
		}
	

    /**名前・コメント入力フォーム・編集対象番号確認フォーム**/
	
    	echo '<form action ="mission_2-4.php" method ="post">';
		echo 	'<input type ="text" name ="name" placeholder ="名前" value ='. $edit_name. '><br>';
		echo 	'<input type ="text" name ="comment" placeholder ="コメント" value ='. $edit_comment. '><br>';
		echo 	'<input type ="hidden" name ="n_edit2" value ='. $n_edit1. '>';		//type ="hidden"でフォームを非表示にする
		echo 	'<input type ="submit" value ="submit"><br>';
		echo '</form>';
	?>

<br>
    <!--削除対象番号入力フォーム-->
	<form action ="mission_2-4.php" method ="post">
		<input type ="text" name ="n_delete" placeholder ="削除対象番号"><br>
		<input type ="submit" value ="delete"><br>
	</form>
<br>
    <!--編集対象番号入力フォーム-->
	<form action ="mission_2-4.php" method ="post">
		<input type ="text" name ="n_edit1" placeholder ="編集対象番号"><br>
		<input type ="submit" value ="編集"><br>
	</form>
<br>
<p>
<?php
date_default_timezone_set('Asia/Tokyo');
/********textファイルへ書き込み********/
if(isset($_POST['name']) && isset($_POST['comment']) && $_POST['name'] !='' && $_POST['comment'] != '' && !(isset($_POST['n_edit2']) && $_POST['n_edit2'] != '')){

	/*投稿数を数える。*/
	
	if(file_exists($filename2)){
		$line = file($filename2);
		$submit_unit = explode("<>", $line[0]);
		$c_submit = intval($submit_unit[1]) + 1;
	}else{
		$c_submit = 1;
	}
	$fp2 = fopen($filename2, 'w+');
	fwrite($fp2, "投稿数は、". "<>". $c_submit. "<>". date("Y/m/d/ H:i:s"));
	fclose($fp2);

	/*投稿されたコメントに番号を付けて、txtに書き出し。*/
	$name = $_POST['name'];
	$comment = $_POST['comment'];

	$fp1 = fopen($filename1, 'a');
	fwrite($fp1, $c_submit. "<>". $name. "<>" . $comment. "<>". date("Y/m/d/ H:i:s"). nl2br(PHP_EOL));
	fclose($fp1);
}


/*******編集対象番号の名前、コメント、を編集する。 **********/
if(isset($_POST['name']) && $_POST['name'] !='' && isset($_POST['comment']) && $_POST['comment'] != '' && isset($_POST['n_edit2']) && $_POST['n_edit2'] != ''){
	$edited_name = $_POST['name'];
	$edited_comment = $_POST['comment'];
	$n_edit2 = $_POST['n_edit2'];

	rename($filename1, $filenameb);
	$fpb = fopen($filenameb, 'r');
	$fp1 = fopen($filename1, 'a');
	while(($line = fgets($fpb)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $n_edit2){
			fwrite($fp1, $n_edit2. "<>". $edited_name. "<>" . $edited_comment. "<>". date("Y/m/d/ H:i:s"). nl2br(PHP_EOL));
			continue;
		}
		fwrite($fp1, $line);
	}
	fclose($fpb);
	fclose($fp1);
	unlink($filenameb);    //編集前のファイルを削除
}





/********入力された数字の行の投稿を削除******/

if(isset($_POST['n_delete']) && $_POST['n_delete'] != ''){    //if(is_int($_POST['n_delete'])){	//文字列が数値かどうか
	rename($filename1, $filenameb);

	$fpb = fopen($filenameb, 'r');
	$fp1 = fopen($filename1, 'a');
	while(($line = fgets($fpb)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $_POST['n_delete'])continue;		//削除対象のコメントが無いとそのまま
		fwrite($fp1, $line);
	}
	fclose($fpb);
	fclose($fp1);
	unlink($filenameb);    //削除前のファイルを削除
}
/***************************************/
?>

</p>
<br>
<p>

<?php
/****************php scripte*************/
if(file_exists($filename1)){
	$file = new SplFileObject($filename1);
	$file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

	foreach ($file as $line) {
		if ($line === false) continue;
		$submit_unit = explode("<>", $line);	//explodeはarrayで格納されるためfor文で1行で表示できるようする。
		foreach($submit_unit as $value){
			echo " $value";
		}
		//echo nl2br(PHP_EOL);
	}
}else{
	echo "file not found";
}

/****************php scripte*************/
?>
	</p>
</body>
</html>
