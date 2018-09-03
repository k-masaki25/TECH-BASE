<?php
/**
 * 
 * pdoでmysqlに接続できる。
 * pdo->query()で引数のsql文をpdoで設定した、データベースで実行する。
 * 
 */
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$passward = 'パスワード';
$pdo = new PDO($dsn, $user, $passward);

$table1 = 'kimura_4_1';
$table2 = 'kimura_4_1_count_comment';

$sql = "CREATE TABLE if not exists kimura_4_1 (id INT, name VARCHAR(32), comment TEXT)";
$stmt = $pdo->query($sql);

$sql = "CREATE TABLE if not exists kimura_4_1_count_comment(CountComment varchar(32), n_comment INT)";
$stmt = $pdo->query($sql);

$sql = "CREATE TABLE if not exists kimura_4_1_password (id INT, password TEXT)";
$stmt = $pdo->query($sql);

$sql = 'select CountComment, n_comment from kimura_4_1_count_comment';
$result = $pdo->query($sql);

if($result){
    $count = $result -> rowCount();
}


if($count === 0){
    $stmt = $pdo -> prepare("INSERT INTO kimura_4_1_count_comment (CountComment, n_comment) VALUES (:CountComment, :value)");
    $stmt->bindParam(':CountComment', $CountComment, PDO::PARAM_STR);
    $stmt->bindValue(':value', 0, PDO::PARAM_INT);
    
    $CountComment = '現在の総コメント数';
    $stmt->execute();
}
?>


<!DOCTYPE html>
<html lang = "ja">


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
		isset($_POST['edit_password']) && $_POST['edit_password'] != ''){

            $id = $_POST['n_edit1'];
            $sql = 'SELECT * FROM kimura_4_1_password where id = :id';
            $sql = $pdo -> prepare($sql);
            $sql -> bindvalue(':id', $id, PDO::PARAM_INT);
            $sql -> execute();
            $results =$sql->fetchAll();
            $password_unit = array();
            foreach($results as $row){
                $password_unit[] = $row['password'];
            }

            if($password_unit[0] === $_POST['edit_password']){
                $edit_id = $_POST['n_edit1'];
            $sql = 'SELECT * FROM kimura_4_1 where id ='. $edit_id;
            $results = $pdo->query($sql);

            $edit_unit = array();
            
            foreach($results as $row){
            
                $edit_unit[] = $row['id'];
                $edit_unit[] = $row['name'];
                $edit_unit[] =$row['comment'];
            }
            $edit_name = $edit_unit[1];
            $edit_comment = $edit_unit[2];
            $n_edit1 = $edit_unit[0];
            }
            
    	}
		

/******************************************
 * 
 * 入力フォーム
 * 
 * 
 ******************************************/


    /**名前・コメント入力フォーム・編集対象番号確認フォーム**/
	
    	echo '<form action ="mission_4-1.php" method ="post">';
		echo 	'<input type ="text" name ="name" placeholder ="名前" value ='. $edit_name. '><br>';
		echo 	'<input type ="text" name ="comment" placeholder ="コメント" value ='. $edit_comment. '><br>';
		echo 	'<input type ="hidden" name ="n_edit2" value ='. $n_edit1. '>';		//type ="hidden"でフォームを非表示にする
		echo 	'<input type ="text" name ="password" placeholder ="パスワード" ><br>';
		echo 	'<input type ="submit" value ="submit"><br>';
		echo '</form>';
	?>

<br>
    <!--削除対象番号入力フォーム-->
	<form action ="mission_4-1.php" method ="post">
		<input type ="text" name ="n_delete" placeholder ="削除対象番号"><br>
		<input type ="text" name ="delete_password" placeholder ="対象パスワード"><br>
		<input type ="submit" value ="delete"><br>
	</form>
<br>
    <!--編集対象番号入力フォーム-->
	<form action ="mission_4-1.php" method ="post">
		<input type ="text" name ="n_edit1" placeholder ="編集対象番号"><br>
		<input type ="text" name ="edit_password" placeholder ="対象パスワード"><br>
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
isset($_POST['password']) && $_POST['password'] != '' &&
!(isset($_POST['n_edit2']) && $_POST['n_edit2'] != '')){

	$sql = 'SELECT * FROM kimura_4_1_count_comment';
    $results = $pdo -> query($sql);
    $n_comment = array();
    foreach($results as $row){
        $n_comment = $row['n_comment'];
    }

    $n_comment = $n_comment[0] + 1;
    $CountComment = '現在の総コメント数';
    $sql2 = $pdo -> prepare("UPDATE kimura_4_1_count_comment SET n_comment = :n_comment where CountComment = :CountComment");
    $sql2 -> bindParam(':CountComment', $CountComment, PDO::PARAM_STR);
    $sql2 -> bindValue(':n_comment', $n_comment, PDO::PARAM_INT);
    $sql2 -> execute();


    $sql1 = $pdo -> prepare("INSERT INTO kimura_4_1 (id, name, comment) VALUES (:id, :name, :comment)");
    
    $id = $n_comment;
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    
    $sql1 -> bindValue(':id', $id, PDO::PARAM_INT);
    $sql1 -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql1 -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql1 -> execute();

    $sql = $pdo -> prepare("INSERT INTO kimura_4_1_password (id, password) VALUES (:id, :password)");
    
    $id = $n_comment;
    $password = $_POST['password'];

    $sql -> bindValue(':id', $id, PDO::PARAM_INT);
    $sql -> bindPARAM(':password', $password, PDO::PARAM_STR);
    $sql -> execute();





}elseif(!(isset($_POST['name']) && $_POST['name'] !='') &&
!(isset($_POST['comment']) && $_POST['comment'] != '' ) &&
!(isset($_POST['password']) && $_POST['password'] != '')){
	//投稿フォームがすべて空欄の場合、何もしない
}else{
	if(!(isset($_POST['name']) && $_POST['name'] !='')){
		echo "名前を入力してください<br>";
	}

	if(!(isset($_POST['comment']) && $_POST['comment'] != '')){
		echo "コメントを入力してください<br>";
	}

	if(!(isset($_POST['password']) && $_POST['password'] != '')){
		echo "パスワードを設定してください<br>";
	}
}


/*********************************
 * 
 * 入力された数字の行の投稿を削除
 *
 *********************************/

if(isset($_POST['n_delete']) && $_POST['n_delete'] != '' && 
isset($_POST['delete_password']) && $_POST['delete_password'] != ''){    //if(is_int($_POST['n_delete'])){	//文字列が数値かどうか

    $id = $_POST['n_delete'];
    $sql = 'SELECT * FROM kimura_4_1_password where id = :id';
    $sql = $pdo -> prepare($sql);
    $sql -> bindvalue(':id', $id, PDO::PARAM_INT);
    $sql -> execute();
    $results =$sql->fetchAll();
    $password_unit = array();
    foreach($results as $row){
        $password_unit[] = $row['password'];
    }
    if($password_unit[0] === $_POST['delete_password']){
        $sql = "DELETE FROM kimura_4_1 WHERE id = :id";
 
        // 削除するレコードのIDは空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
         
        $delete_id = $_POST['n_delete'];
        
        // 削除するレコードのIDを配列に格納する
        $params = array(':id'=>$delete_id);
         
        // 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);

        $sql = "DELETE FROM kimura_4_1_password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $params = array(':id'=>$delete_id);
        $stmt->execute($params);
         
        // 削除完了のメッセージ
        echo '削除完了しました'. "<br>";
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
isset($_POST['password']) && $_POST['password']){

	$sql = $pdo -> prepare("UPDATE kimura_4_1 SET name = :name, comment = :comment where id = :id");
    $id = intval($_POST['n_edit2']);
    $name = $_POST['name'];
    $comment = $_POST['comment'];


    $sql -> bindValue(':id', $id, PDO::PARAM_INT);
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> execute();

    $sql = $pdo -> prepare("UPDATE kimura_4_1_password SET password = :password where id = :id");
    $id = intval($_POST['n_edit2']);
    $password = $_POST['password'];

    $sql -> bindValue(':id', $id, PDO::PARAM_INT);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $sql -> execute();

}
?>

</p>
<br>
<p>

<?php
$sql = 'SELECT * FROM kimura_4_1';
$results = $pdo -> query($sql);

$id = array();
$name = array();
$comment = array();
$comment_unit = array();

foreach($results as $row){

    $id[] = $row['id'];
    $name[] = $row['name'];
    $comment[] =$row['comment'];

    //$rowの中にはテーブルのカラムが入る
    echo $row['id']. ' ';
    echo $row['name']. ' ';
    echo $row['comment']. '<br>';
}

    



echo "<hr>";

$sql = 'SELECT * FROM kimura_4_1_count_comment';
$results = $pdo -> query($sql);
$n_comment = array();
foreach($results as $row){
    $n_comment = $row['n_comment'];

    echo $row['CountComment'];
    echo $row['n_comment']. '<br>';
}
?>
	</p>
</body>
</html>