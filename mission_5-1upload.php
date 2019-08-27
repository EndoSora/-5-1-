<?php



//接続
$dsn= 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));	//error出させる


//テーブルを作る
$sql = "CREATE TABLE IF NOT EXISTS test3"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time DATETIME,"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);



//書き込む

if ( isset ($_POST ["submit"] ) == true ){

	$name = ( isset ($_POST["name"] ) == true )?$_POST["name"]:"";
	$comment = ( isset ($_POST ["comment"] ) == true )?$_POST["comment"]:"";		//三項演算子
	$pass = ( isset ($_POST ["password"] ) == true )?$_POST["password"]:"";

	if (empty($name)){
	echo "名前を入力しよう！<br>";
	}

	if (empty($comment)){
	echo "コメントを入力しよう！<br>";
	}

	if(!empty($name)&&!empty($comment)){

	$time= new DateTime();
	$time= $time->format("Y-m-d H:i:s");
	$sql=$pdo->prepare("INSERT INTO test3 (name,comment,time,pass) VALUES (:name, :comment, :time, :pass)");
	$sql->bindParam('name',$name,PDO::PARAM_STR);
	$sql->bindParam('comment',$comment,PDO::PARAM_STR);
	$sql->bindValue('time',$time,PDO::PARAM_STR);
	$sql->bindParam('pass',$pass,PDO::PARAM_STR);
	$sql->execute();
	}

}

//削除機能

if ( isset ($_POST ["delete"] ) == true ){

	$delnumber = ( isset ($_POST ["delnumber"] ) == true )?$_POST["delnumber"]:"";		//三項演算子
	$delpassword = ( isset ($_POST ["delpassword"] ) == true )?$_POST["delpassword"]:"";

	if (empty($delnumber)){
	echo "削除番号を入力しよう！<br>";
	}
	if (empty($delpassword)){
	echo "パスワードを入力しよう！<br>";
	}

	if(!empty($delnumber)&&!empty($delpassword)){

		$sql = 'SELECT * FROM test3';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();

		foreach ($results as $row){

			if ($row['id']==$delnumber&&$row['pass']==$delpassword){
				$id = $delnumber;
				$sql = 'delete from test3 where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				echo "削除したよ！<br>";
			}
			elseif ($row['id']==$delnumber&&$row['pass']!==$delpassword){
				echo "パスワードが違うぞい<br>";
			}
		}
	}
}



//編集する

if ( isset ($_POST ["edit"] ) == true ){

	$editnumber = ( isset ($_POST ["editnumber"] ) == true )?$_POST["editnumber"]:"";		//三項演算子
	$editpassword = ( isset ($_POST ["editpassword"] ) == true )?$_POST["editpassword"]:"";
	$editcomment = ( isset ($_POST ["editcomment"] ) == true )?$_POST["editcomment"]:"";		//三項演算子
	$editname = ( isset ($_POST ["editname"] ) == true )?$_POST["editname"]:"";

	if (empty($editnumber)){
	echo "編集番号を入力しよう！<br>";
	}
	if (empty($editpassword)){
	echo "パスワードを入力しよう！<br>";
	}
	if (empty($editcomment)){
	echo "編集後のコメントを入力しよう！<br>";
	}
	if (empty($editname)){
	echo "編集後の名前を入力しよう！<br>";
	}

	if(!empty($editnumber)&&!empty($editpassword)&&!empty($editcomment)&&!empty($editname)){

		$sql = 'SELECT * FROM test3';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();

		foreach ($results as $row){

			if ($row['id']==$editnumber&&$row['pass']==$editpassword){
				$id = $editnumber;
				$name = $editname;
				$comment=$editcomment;
				$sql = 'update test3 set name=:name,comment=:comment where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			elseif ($row['id']==$editnumber&&$row['pass']!==$editpassword){
				echo "パスワードが違うぞい<br>";
			}
		}
	}
}





//表示する
$sql='SELECT*FROM test3';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach ($results as $row){
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['time'].' ';
	echo"<hr>";
}


?>	




<html>


<form method = "post" action = "mission_5-1.php">

<?php

echo "<br /><br /><新規投稿><br />";
echo "パスワードがないと後で編集・削除できないよ！<br/>";
?>

<input name = "name"  placeholder = "名前">
<input name = "comment"  placeholder = "コメント">
<input type = "password" name = "password"  placeholder = "パスワード">

<input type = "submit" name = "submit"><br><br>

<?php
echo "<削除機能><br />";
?>

<input type = "number" name = "delnumber" placeholder = "削除番号">
<input type = "password" name = "delpassword" placeholder = "パスワード">

<input type = "submit" name = "delete"  value = "削除"><br><br>


<?php
echo "<編集機能><br />";
?>
<input type = "number" name = "editnumber" placeholder = "編集番号">
<input type = "password" name = "editpassword"  placeholder = "パスワード"><br>



<input name = "editname"  placeholder = "編集後名前">
<input name = "editcomment"  placeholder = "編集後コメント">


<input type = "submit" name = "edit"  value = "編集"><br><br>


</form>


</html>



