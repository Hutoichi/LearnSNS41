<?php 
  	// require(dbconnect)
  	require('dbconnect.php');

    // feed_idを所得
    $feed_id = $_GET["feed_id"];

    // 更新ボタンが押されたら（POST送信されたデータが存在したら）
    if (!empty($_POST)) {
      // Update文でDBに保存
      // UPDATE テーブル名 SET カラム名 = 値
      $update_sql = "UPDATE `feeds` SET `feed` = ? WHERE `feeds`.`id` = ?";

      $data = array($_POST["feed"],$feed_id);
      // sql文実行
      $stmt = $dbh -> prepare($update_sql);
      $stmt->execute($data);

      // 一覧に戻る
      header("Location: timeline.php");

    }

    // ２．SQL文を実行する オーダーする
    $sql = "SELECT `feeds`.* , `users`.`name` , `users`.`img_name` FROM `feeds` LEFT JOIN `users` ON `feeds` . `user_id`=`users` . `id` WHERE `feeds`. `id` = $feed_id";

    // SQL インジェクション対策
    $data = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $feeds = $record;


    // ３．データベースを切断する 電話切る
    $dbh = null;


?>




<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px;">
	<div class="container">
		<div class="row">
			<!-- ここにコンテンツを書く -->
			<div class="col-xs-4 col-xs-offset-4">
				<form class="form-group" method="post">
					<img src="user_profile_img/<?php echo $feeds["img_name"] ;?>" width="60">
					<?php echo $feeds["name"] ;?><br>
					<?php echo $feeds["created"] ;?><br>
					<textarea name="feed" class="form-control"><?php echo $feeds["feed"] ;?></textarea>
					<input type="submit" submit="更新" class="btn btn-warning btn-xs">
				</form>
			</div>
		</div>
	</div>

  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>