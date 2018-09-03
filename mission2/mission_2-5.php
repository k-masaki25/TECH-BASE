<!DOCTYPE html>
<html lang = "ja">
<?php
$filename1 = 'mission_2-5_kimura.txt';
$filenameb1 = 'before_mission_2-5_kimura.txt';
$filename2 = 'mission_2-5_post_count.txt';
$filename3 = 'mission_2-5.php';
$filename4 = 'mission_2-5_passward.txt';
$filenameb4 = 'before_mission_2-5_kimura.txt';
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

		/**
		 * このif文で、editするコメントがぞんざいした場合、編集する名前とコメントを変数に入れているが、
		 * 関数にするにはどうしたらよいか？
		*/

		if(isset($_POST['n_edit1']) && $_POST['n_edit1'] != '' && 
		isset($_POST['edit_passward']) && $_POST['edit_passward'] != ''){
			if(ConfirmCommentNum($_POST['n_edit1'])){
				if(ConfirmPass($_POST['n_edit1'], $_POST['edit_passward'])){
					$file = new SplFileObject($filename1);
					$file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
					foreach ($file as $line) {
						if ($line === false) continue;
						else{
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
				}else{
					echo "正しいパスワードを入力してください";
				}
			}else{
				echo "対象のコメントがありません";
			}
		}

/******************************************
 * 
 * 入力フォーム
 * 
 * 
 ******************************************/


    /**名前・コメント入力フォーム・編集対象番号確認フォーム**/
	
    	echo '<form action ="mission_2-5.php" method ="post">';
		echo 	'<input type ="text" name ="name" placeholder ="名前" value ='. $edit_name. '><br>';
		echo 	'<input type ="text" name ="comment" placeholder ="コメント" value ='. $edit_comment. '><br>';
		echo 	'<input type ="hidden" name ="n_edit2" value ='. $n_edit1. '>';		//type ="hidden"でフォームを非表示にする
		echo 	'<input type ="text" name ="passward" placeholder ="パスワード" ><br>';
		echo 	'<input type ="submit" value ="submit"><br>';
		echo '</form>';
	?>

<br>
    <!--削除対象番号入力フォーム-->
	<form action ="mission_2-5.php" method ="post">
		<input type ="text" name ="n_delete" placeholder ="削除対象番号"><br>
		<input type ="text" name ="delete_passward" placeholder ="対象パスワード"><br>
		<input type ="submit" value ="delete"><br>
	</form>
<br>
    <!--編集対象番号入力フォーム-->
	<form action ="mission_2-5.php" method ="post">
		<input type ="text" name ="n_edit1" placeholder ="編集対象番号"><br>
		<input type ="text" name ="edit_passward" placeholder ="対象パスワード"><br>
		<input type ="submit" value ="編集"><br>
	</form>
<br>
<p>
<?php
date_default_timezone_set('Asia/Tokyo');	//タイムゾーンを設定

/*********************************
 * 
 *コメントの投稿 AND textファイルへ書き込み
 *
 * *******************************/
if(isset($_POST['name']) && $_POST['name'] !='' && 
isset($_POST['comment']) && $_POST['comment'] != '' && 
isset($_POST['passward']) && $_POST['passward'] != '' &&
!(isset($_POST['n_edit2']) && $_POST['n_edit2'] != '')){

	/*新しく投稿されたコメントの番号を計算する。*/
	$n_next_submit = CountComment() + 1;

	/*投稿数を数えてpost_count.txtに書き出し*/
	$fp2 = fopen($filename2, 'w+');
	fwrite($fp2, "投稿数は、". "<>". $n_next_submit. "<>". date("Y/m/d/ H:i:s"));
	fclose($fp2);

	/*投稿されたコメントに番号を付けて、kimura.txtに書き出し*/
	$name = $_POST['name'];
	$comment = $_POST['comment'];

	$fp1 = fopen($filename1, 'a');
	fwrite($fp1, $n_next_submit. "<>". $name. "<>" . $comment. "<>". date("Y/m/d/ H:i:s"). "<>". nl2br(PHP_EOL));
	fclose($fp1);

	//投稿されたコメントの番号とパスワードをpassward.txtに書き出し
	$passward = $_POST['passward'];
	$fp4 = fopen($filename4, 'a');
	fwrite($fp4, $n_next_submit. "<>". $passward. "<>". nl2br(PHP_EOL));
	fclose($fp4);
}elseif(!(isset($_POST['name']) && $_POST['name'] !='') &&
!(isset($_POST['comment']) && $_POST['comment'] != '' ) &&
!(isset($_POST['passward']) && $_POST['passward'] != '')){
	//投稿フォームがすべて空欄の場合、何もしない
}else{
	if(!(isset($_POST['name']) && $_POST['name'] !='')){
		echo "名前を入力してください<br>";
	}

	if(!(isset($_POST['comment']) && $_POST['comment'] != '')){
		echo "コメントを入力してください<br>";
	}

	if(!(isset($_POST['passward']) && $_POST['passward'] != '')){
		echo "パスワードを設定してください<br>";
	}
}


/*********************************
 * 
 * 入力された数字の行の投稿を削除
 *
 *********************************/

if(isset($_POST['n_delete']) && $_POST['n_delete'] != '' && 
isset($_POST['delete_passward']) && $_POST['delete_passward'] != ''){    //if(is_int($_POST['n_delete'])){	//文字列が数値かどうか
	if(ConfirmCommentNum($_POST['n_delete'])){
		if(ConfirmPass($_POST['n_delete'], $_POST['delete_passward'])){
			CommentDelete($_POST['n_delete']);
		}else{
			echo "正しいパスワードを入力してください。";
		}
	}else{
		echo "対象のコメントがありません。";
	}
	
}

/******************************************
 * 
 * 編集対象番号の名前、コメント、を編集する。 
 * 
 * ***************************************/
if(isset($_POST['name']) && $_POST['name'] !='' && 
isset($_POST['comment']) && $_POST['comment'] != '' && 
isset($_POST['n_edit2']) && $_POST['n_edit2'] != '' && 
isset($_POST['passward']) && $_POST['passward']){

	CommentEdit($_POST['name'], $_POST['comment'], $_POST['n_edit2']);
	PasswardEdit($_POST['n_edit2'], $_POST['passward']);
}
?>

</p>
<br>
<p>

<?php
/***********************************
 * 
 * kimura.txtの内容を表示する。
 * 
 ***********************************/
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

$CommentSum = CountComment();
echo '<p>現在までに、'. $CommentSum. '回コメントが投稿されました。</p>';
?>
	</p>
</body>
</html>







<?PHP
/****************************
 * Functions
 * *************************
 * CountComment()
 * 
 * CommentEdit( , , )
 * 
 * CommentDelete()
 ****************************/

 //コメントの総数を返す
function CountComment(){
	global $filename2;

	if(file_exists($filename2)){
		$line = file($filename2);
		$submit_unit = explode("<>", $line[0]);
		$c_submit = intval($submit_unit[1]);
	}else{
		$c_submit = 0;
	}
	return $c_submit;
}

//コメントを編集する
function CommentEdit($edited_name, $edited_comment, $n_edit2){
	global $filename1;
	global $filenameb1;
	rename($filename1, $filenameb1);
	$fpb1 = fopen($filenameb1, 'r');
	$fp1 = fopen($filename1, 'a');
	while(($line = fgets($fpb1)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $n_edit2){
			fwrite($fp1, $n_edit2. "<>". $edited_name. "<>" . $edited_comment. "<>". date("Y/m/d/ H:i:s"). nl2br(PHP_EOL));
			continue;
		}
		fwrite($fp1, $line);
	}
	fclose($fpb1);
	fclose($fp1);
	unlink($filenameb1);    //編集前のファイルを削除
}

function PasswardEdit($n_edit2, $edit_pass){
	global $filename4;
	global $filenameb4;
	rename($filename4, $filenameb4);
	$fpb4 = fopen($filenameb4, 'r');
	$fp4 = fopen($filename4, 'a');
	while(($line = fgets($fpb4)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $n_edit2){
			fwrite($fp4, $n_edit2. "<>". $edit_pass. "<>". nl2br(PHP_EOL));
			continue;
		}
		fwrite($fp4, $line);
	}
	fclose($fpb4);
	fclose($fp4);
	unlink($filenameb4);    //編集前のファイルを削除
}

//コメントを削除する
function CommentDelete($n_delete){
	global $filename1;
	global $filenameb1;

	rename($filename1, $filenameb1);

	$fpb = fopen($filenameb1, 'r');
	$fp1 = fopen($filename1, 'a');
	while(($line = fgets($fpb)) !== false){
		$submit_unit = explode("<>", $line);
		if($submit_unit[0] === $n_delete)continue;		//削除対象のコメントが無いと元のtxtファイルと同じものになる
		fwrite($fp1, $line);
	}
	fclose($fpb);
	fclose($fp1);
	unlink($filenameb1);    //削除前のファイルを削除
}

//対象のコメントの存在を確認する
function ConfirmCommentNum($n_comment){
	global $filename4;
	$ConfirmTrigger = False;
	$fp4 = fopen($filename4, 'r');
	while(($line = fgets($fp4)) !==False){
		$pass_unit = explode("<>", $line);
		if($pass_unit[0] === $n_comment){
			$ConfirmTrigger = True;
		}
	}
	fclose($fp4);
	return $ConfirmTrigger;
}

//パスワードを確認する
function ConfirmPass($n_comment, $passward){
	global $filename4;
	$ConfirmTrigger = False;
	$fp4 = fopen($filename4, 'r');
	while(($line = fgets($fp4)) !==False){
		$pass_unit = explode("<>", $line);
		if($pass_unit[0] === $n_comment && $pass_unit[1] === $passward){
			$ConfirmTrigger = True;
		}
	}
	fclose($fp4);
	return $ConfirmTrigger;
}
?>
