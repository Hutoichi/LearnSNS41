<?php 

// SESSION変数を使えるようにする
session_start();

// DBに接続
require('dbconnect.php');


// follower_idを取得
$follower_id = $_GET["follower_id"];

// folllowボタンを押した人のid
$user_id = $_SESSION["id"];

// SQL文作成
$sql = "DELETE FROM `followers` WHERE `user_id`=? AND `follower_id`=?;";


// SQL実行
$data = array($_SESSION['id'],$follower_id);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);


// profileに戻る
header("Location: profile.php?user_id=".$follower_id);



 ?>