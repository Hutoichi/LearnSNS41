<?php 

// 関数

// 基本構造

// 関数を宣言する書き方
// function 関数名（引数１、引数２、...）{
// 	//実行される処理
// }

// ・・・「function」は関数という意味なので宣言時に

// //例）２つの数字を計算する関数
// function add_number(x,y){
// 	echo x + y;
// }

// →関数の実行（３が表示される）
// add_number(1,2);

// //2つの数字を計算する関数
// return 戻り値;（返り値）

// function mi_number(x,y){
// 	return x-y;
// }

// //関数の実行
// $answer = mi_number(5,2);

// //呼び出した側で計算結果を続けて使える
// $score = 100 - $answer;




// DBコネクトとの違いは「処理の順番」を自由に選択できる（dbconnectはかいたときにすべてを実行してしまう）

	function get_signin_user($dbh,$user_id){
		$sql = 'SELECT * FROM `users` WHERE `id`=?';
		$data = array($user_id);
		$stmt = $dbh->prepare($sql);
		$stmt->execute($data);

		// signin_userに取り出したレコードを代入する
		$signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

		return $signin_user;
	}

	function check_signin($user_id){
		if (!isset($_SESSION["id"])) {
			header("Location: signin.php");
			exit();
		}
	}

 ?>