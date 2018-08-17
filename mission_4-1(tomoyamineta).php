<html>
<head>
<?php
	//PDOオブジェクトを作成するため、FTPアカウントを変数にする
	$dsn = 'mysql:dbname=データベース名;host=localhost'; 
	$user = 'ユーザー名'; 
	$password = 'パスワード'; 
	//PDOオブジェクトの作成しデータベースに接続
	$pdo = new PDO($dsn,$user,$password);
	$stmt = $pdo -> query('SET NAMES utf8');
	//テーブルの作成
	$sql= "CREATE TABLE deta2" 
	." (" 
	. "id INT AUTO_INCREMENT PRIMARY KEY, "
	. "name char(32), "
	. "comment TEXT, "
	. "time DATETIME,"
	. "password TEXT"
	.");" ;
	//$sqlの発行
	$stmt = $pdo->query($sql);

	//テーブル一覧を表示させる
	$sql ='SHOW TABLES'; 
	$result = $pdo -> query($sql); 
	foreach ($result as $row){ 
		echo $row[0]; 
		echo '<br>'; 
	} 
	echo "<hr>";

	if(isset($_POST['name']) && isset($_POST['comment']) && $_POST['password']!='' && empty($_POST['id'])){
		//insertを行う
		$sql = $pdo -> prepare("INSERT INTO deta2 (id,name,comment,time,password) VALUES (NULL,:name, :comment, :time, :password)"); 

		$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);  
		$sql -> bindParam(':time', $times, PDO::PARAM_STR);  
		$sql -> bindParam(':password', $pass, PDO::PARAM_STR); 
		$times = date("y/m/d h:m:s");
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$pass = $_POST['password'];
		$sql -> execute();

		//detaの全件全列を参照
		$sql = 'SELECT * FROM deta2'; 
		$results = $pdo -> query($sql); 
		//全データを表示する
		foreach($results as $row){   
			echo $row['id'].' ';    
			echo $row['name'].' ';   
			echo $row['comment'].' ';   
			echo $row['time'].'<br>';   
		}
	}
	//削除機能
	if(isset($_POST['clear']) && isset($_POST['pass'])){
		$delete = $_POST['clear'];
		$pass = $_POST['pass'];

		$sql = 'SELECT * FROM deta2'; 
		$results = $pdo -> query($sql);
		foreach($results as $row){
			//もし、$row['id']が$deleteでなければそのままのデータになる
			if($row['id']!=$delete){
				echo $row['id'].' ';    
				echo $row['name'].' ';   
				echo $row['comment'].' ';   
				echo $row['time'].'<br>'; 
			} else{
				if($row['password']!=$pass){
					echo $row['id'].' ';    
					echo $row['name'].' ';   
					echo $row['comment'].' ';   
					echo $row['time'].'<br>'; 
				}else{
					$sql = "delete from deta2 where id = $delete";  
					$result = $pdo->query($sql);
				}
			}
		}
	}
	//編集機能
	if(isset($_POST['edit']) && isset($_POST['pass1'])){
		$edit = $_POST['edit'];
		$pass = $_POST['pass1'];
		$sql = 'SELECT * FROM deta2'; 
		$results = $pdo -> query($sql);

		foreach($results as $row){
			if($row['id']==$edit && $row['password']==$pass){
				$number = $row['id'];
		 		$name2 = $row['name'];
				$comment2 = $row['comment'];
				$pass2 = $row['password'];
				echo $row['id'].' ';    
				echo $row['name'].' ';   
				echo $row['comment'].' ';   
				echo $row['time'].'<br>'; 
			} else{
				echo $row['id'].' ';    
				echo $row['name'].' ';   
				echo $row['comment'].' ';   
				echo $row['time'].'<br>'; 
			}
		}
	}
	if(!empty($_POST['id']) && !empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password'])){
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$id = $_POST['id'];

		//全件前列を参照する
		$sql = 'SELECT * FROM deta2'; 
		$results = $pdo -> query($sql);
		
		foreach($results as $row){
			//$row['id']と$idの番号が同じ番号ならば
			if($row['id'] == $id){
				//updateでデータを編集
				$sql = "update deta2 set name='$name',comment='$comment' where id = $id"; 
				$result = $pdo->query($sql);
				//編集した後のデータを表示	
				echo $row['id'].' ';    
				echo $name.' ';   
				echo $comment.' ';   
				echo $row['time'].'<br>';
 			}else{
				//でなければデータは変化しない
				echo $row['id'].' ';    
				echo $row['name'].' ';   
				echo $row['comment'].' ';   
				echo $row['time'].'<br>'; 	
			}				
		}		
	}	

	?>
	<form action="mission_4-1(tomoyamineta).php" method="post">
		名前:<br/>
 		<input type="text" name="name" size="30" value="<?php echo $name2;?>"><br/>
		コメント:<br/>
 		<input type ="text" name ="comment" size ="30" value = "<?php echo $comment2;?>"><br/>
		<input type = "hidden" name = "id" value = "<?php echo $number; ?>"><br/>
		パスワード:<br/>
		<input type = "text" name = "password"  size ="30"value = "<?php echo $pass2; ?>"><br/>
		<input type="submit" value="送信する" ><br/>
	</form>

	<form action="mission_4-1(tomoyamineta).php" method="post">
	 	削除対象番号:<br/>
		<input type = "text" name = "clear" size = "30"><br/>
		パスワード:<br/>
		<input type = "text" name = "pass"  size ="30"><br/>
		<input type = "submit" value = "削除">
	</form>

	<form action="mission_4-1(tomoyamineta).php" method="post">
		編集対象番号:<br/>
		<input type = "text" name = "edit" size = "30"><br/>
		パスワード:<br/>
		<input type = "text" name = "pass1"  size ="30"><br/>
		<input type = "submit" value = "編集">
	</form>
</body>
</html>
