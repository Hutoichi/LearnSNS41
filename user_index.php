<?php 

  // sessionを使うために「session_start();」を使用する
  session_start();

  // require(dbconnect)
  require('dbconnect.php');
  require('function.php');

  $signin_user = get_signin_user($dbh,$_SESSION["id"]);

  // SELECT usersテーブルから$_SESSIONの中に保存されているidを使って一件だけ取り出す
  $sql = "SELECT * FROM `users`";
  $data = array($_SESSION['id']);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);

  $users = array();


// fetchから取り出す
  while (true) {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    // false＝データがなくなったときを指す

    if ($record == false) {
      break;
    }
      // SELECT文でfeedsテーブルからuser_idを取ってくる
    $feed_sql = "SELECT COUNT(`user_id`) AS `comment_cnt` FROM `feeds` WHERE `user_id`=?";
    $feed_data = array($record["id"]);
    $feed_stmt = $dbh->prepare($feed_sql);
    $feed_stmt->execute($feed_data);

    $feed = $feed_stmt -> fetch(PDO::FETCH_ASSOC);
    $record["comment_cnt"] = $feed["comment_cnt"];

    // usersの箱の中に$recordを代入する
    $users[] = $record;
  }

  $c = count($users);

  foreach ($users as $user) {

  }

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

<?php include("navbar.php"); ?>

<body style="margin-top: 60px; background: #E4E6EB;">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">

        <?php foreach ($users as $user) { ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $user['img_name']; ?>" width="80">
              </div>
              <div class="col-xs-11">
                <?php echo $user['name']; ?><br>
                <a href="profile.php?user_id=<?php echo $user['id']; ?>" style="color: #7F7F7F;"><?php echo $user['created']; ?></a><br>
                <span class="comment_count">つぶやき数：<?php echo $user['comment_cnt']; ?></span>
              </div>
            </div>
          </div><!-- thumbnail -->
          <?php } ?>

      </div><!-- class="col-xs-12" -->
    </div><!-- class="row" -->
  </div><!-- class="cotainer" -->
</body>
</html>