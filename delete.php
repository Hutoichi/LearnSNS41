<?php 

	// DBに接続
	require('dbconnect.php');

	$feed_id = $_GET['feed_id'];

	// Delete文（sql文）
	$sql = "DELETE FROM `feeds` WHERE `feeds`.`id` = ?";

	// sql実行
	$data = array($feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $dbh = null;


	// 一覧に戻る
	header("Location: timeline.php");


?>