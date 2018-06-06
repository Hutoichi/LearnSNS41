<?php 
session_start();

// DBに接続
require('dbconnect.php');

echo "<pre>";
var_dump($_POST);
echo "</pre>";

$login_user_id = $_SESSION["id"];
$comment = $_POST["write_comment"];
$feed_id = $_POST["feed_id"];

// コメントをInsertするSQL文作成
$sql = "INSERT INTO `comments` (`comment`, `user_id`, `feed_id` , `created`) VALUES (?, ?, ?, now())";


// SQL文実行
$data = array($comment,$login_user_id,$feed_id);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

// feedsテーブルにcommentカウントをUpdateする
// sql文を作成
// ヒント：上のインサートですでにコメントを挿入してる＝+1すればいい？
$update_sql = "UPDATE `feeds` SET `comment_count` = `comment_count`+1 WHERE `id` = ?";

// sql文実行
$update_data = array($feed_id);
$update_stmt = $dbh->prepare($update_sql);
$update_stmt->execute($update_data);



// timeline.php(一覧)にもどる
header("Location: timeline.php");



?>