<?php

	// sessionを使うために「session_start();」を使用する
	session_start();

	// require(dbconnect)
	require('dbconnect.php');
  require('function.php');

	// // SELECT usersテーブルから$_SESSIONの中に保存されているidを使って一件だけ取り出す
	// $sql = 'SELECT * FROM `users` WHERE `id`=?';
	// $data = array($_SESSION['id']);
 //  $stmt = $dbh->prepare($sql);
 //  $stmt->execute($data);

	// // $signin_userに取り出したレコードを代入する
 //  $signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

  $signin_user = get_signin_user($dbh,$_SESSION["id"]);

	// 写真と名前をレコードから取り出す
	$img_name = $signin_user['img_name'];
	$name = $signin_user['name'];


	$errors = array();

	// 投稿内容の空チェック
  // ボタンを押したとき
	if (!empty($_POST)) {
	        $feed = $_POST['feed'];

		if ($feed != '') {
			// 空じゃないときに投稿処理
      // 何もないのにINSERTしたら整理できない＝行動を絞る
  			$sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?, `created`=NOW()';
  			$data = array($feed, $signin_user['id']);
		    $stmt = $dbh->prepare($sql);
		    $stmt->execute($data);

		    header('Location: timeline.php');
        exit();
		}
		else {
			// 空のときエラーがでる
			  $errors['feed'] = 'blank';
	    }
	}

  // pageが進むと次の5件を取得する
  $page = ''; //ページ番号が入る変数
  $page_row_number = 5; //1ページあたりに表示するデータの数

  if (isset($_GET['page'])) {
    // GET送信のページ数にしたがって代入する
    $page = $_GET['page'];
  }
  else{
    // GET送信されているページ数がない場合、1ページ目とみなす
    $page = 1;
  }

  // データを取得する開始番号を計算
  $start = ($page -1) * $page_row_number;

  // if文を使って「検索ボタンが押された時・押されてない時」の2パターンに分ける
  // 押された時＝GET送信されたserch_worldというキーがある
  // →あいまい検索、通常→全件取得

  if (isset($_GET['search_word']) == true) {
    $sql = 'SELECT `feeds`.* , `users`.`name` , `users`.`img_name` FROM `feeds` LEFT JOIN `users` ON `feeds` . `user_id`=`users` . `id` WHERE `feeds`.`feed` LIKE "%'.$_GET['search_word'].'%" ORDER BY `f` . `created` DESC';
  }

  else {
  // LEFT JOINで全件取得
  // LIMIT＝「0から始まる」,「取得したい件数」
    $sql = "SELECT `feeds`.* , `users`.`name` , `users`.`img_name` FROM `feeds` LEFT JOIN `users` ON `feeds` . `user_id`=`users` . `id` WHERE 1 ORDER BY `created` DESC LIMIT $start,$page_row_number";
  }

    $data = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    // 表示用の配列を初期化＝繰り返し文の中に入れない
    $feeds = array();

    // fetchから取り出す
    while (true) {
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      // false＝データがなくなったときを指す

      if ($record == false) {
          break;
      }


      // ①（全部取得）commentテーブルから今取得できているfeedに対してのデータを取得
      $comment_sql = "SELECT `c`.* , `u`.`name` , `u`.`img_name` FROM `comments` AS `c` LEFT JOIN `users` AS `u` ON `c`.`user_id` = `u`.`id` WHERE `feed_id` = ?";

      $comment_data = array($record["id"]);
      // sql実行
      $comment_stmt = $dbh->prepare($comment_sql);
      $comment_stmt->execute($comment_data);

      // ②全てのコメントをとるためになくなるまでwhileで取得
      $comments_array = array();

      while (true) {
        $comment_record = $comment_stmt->fetch(PDO::FETCH_ASSOC);

        if ($comment_record == false) {
          break;
        }

        // 重要！！！！！！！！！！！！！！
        // ③（コメントだけを取得）取得したコメントのデータを追加代入
        $comments_array[] = $comment_record; 
      }

        // 重要！！！！！！！！！！！！！！
        // ④1行分の変数（連想配列）に、新しくcommentsというキーを追加してコメント情報を代入
      $record["comments"] = $comments_array;

      // echo "<pre>";
      // var_dump($feeds);
      // echo "</pre>";


      // SQL文を実行
      $like_sql = "SELECT COUNT(*) AS `like_cnt` FROM `likes` WHERE `feed_id` = ?";

      // 上で作成したSQL文を上書きされないように名前を変える
      $like_data = array($record["id"]);
      $like_stmt = $dbh->prepare($like_sql);
      $like_stmt->execute($like_data);

      // like数を取得
      $like = $like_stmt -> fetch(PDO::FETCH_ASSOC);


      // []は配列の要素追加（構文）
      // 上書きする場合は[]に数字を入れる
      $record["like_cnt"] = $like["like_cnt"];


      // like済みか判断するSQLを作成
      $like_flag_sql = "SELECT COUNT(*) AS `like_flag` FROM `likes` WHERE `user_id` = ? AND `feed_id` = ? ";

      // SQL実行
      $like_flag_data = array($_SESSION["id"],$record["id"]);
      $like_flag_stmt = $dbh->prepare($like_flag_sql);
      $like_flag_stmt->execute($like_flag_data);

      $like_flag = $like_flag_stmt -> fetch(PDO::FETCH_ASSOC);

      // 「いいね！」がついてるか、ついてないかで表示を変える
      if ($like_flag["like_flag"] > 0) {
        $record["like_flag"] = 1;
      }else{
        $record["like_flag"] = 0;
      }

      // いいね済みのみのリンク押された時は、配列にすでにいいね！しているものだけを代入する
      if (isset($_GET["feed_select"]) && ($_GET["feed_select"] == "likes") && ($record["like_flag"] == 1)) {
        $feeds[] = $record;
      }

      if(!isset($_GET["feed_select"])){
        $feeds[] = $record;
      }

      // 新着順を押した時に全権表示
      if (isset($_GET["feed_select"]) && ($_GET["feed_select"] == "news")) {
        $feeds[] = $record;
      }


      $c = count($feeds);

      foreach ($feeds as $feed) {
      }
  }


// ログアウトから戻ってきたときに戻れないようにする
	if (empty($_SESSION)) {
		header("Location: signin.php");
		exit();
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
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <?php if(isset($_GET["feed_select"]) && ($_GET["feed_select"]=="likes")){ ?>
          <li><a href="timeline.php?feed_select=news">新着順</a></li>
          <li class="active"><a href="timeline.php?feed_select=likes">いいね！済み</a></li>

          <?php }else{ ?>
          <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
          <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
          <?php } ?>
          <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
              <?php if(isset($errors['feed']) && $errors['feed'] == 'blank') { ?>
              <p class="alert alert-danger">投稿データを入力してください</p>
              <?php } ?>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>

        <!-- ここを繰り返したい -->
          <?php foreach ($feeds as $feed) { ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed['img_name'];?>" width="40">
              </div>
              <div class="col-xs-11">
                <?php echo $feed['name'];?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $feed['created'];?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $feed['feed'];?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">

              <?php if($feed["like_flag"] == 0 ) { ?>
                <a href="like.php?feed_id=<?php echo $feed['id']; ?>">
                    <input type="hidden" name="like" value="like">
                    <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！</button>
                </a>
              <?php } else {?>
                <a href="unlike.php?feed_id=<?php echo $feed['id']; ?>">
                    <input type="hidden" name="like" value="like">
                    <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！を取り消す</button>
                </a>
              <?php  } ?>

                <span class="like_count"><?php if($feed["like_cnt"] != 0){echo "いいね数：" . $feed["like_cnt"]; }?></span>
                <a href="#collapseComment<?php echo $feed["id"]; ?>" data-toggle="collapse" aria-expamded="false"><span class="comment_count">コメント数：<?php echo $feed["comment_count"]; ?></span></a>

                <!-- 編集・削除権限をログインしてる人だけに表示される -->
                <?php if ($feed["user_id"] == $_SESSION["id"]) {?>

                  <a href="edit.php?feed_id=<?php echo $feed["id"]; ?>" class="btn btn-success btn-xs">編集</a>
                  <a onclick="return confirm('ほんとに消しちゃうの?')" href="delete.php?feed_id=<?php echo $feed["id"] ?>" class="btn btn-danger btn-xs">削除</a>

                <?php } ?>

              </div>

              <!-- コメントが押されたら表示される領域 -->
              <!-- <div class="collapse" id="collapseComment"></div>
                表示の確認！ -->
              <?php include("comment_view.php"); ?>
            </div>
          </div>
          <?php } ?>
          <!-- ここを繰り返したい -->

        <div aria-label="Page navigation">
          <ul class="pager">
            <?php if ($page>=2) { ?>
            <li class="previous"><a href="timeline.php?page=<?php echo $page-1;?>"><span aria-hidden="true">&larr;</span> Newer</a></li>
            <?php } ?>

            <li class="next"><a href="timeline.php?page=<?php echo $page+1; ?>">Older <span aria-hidden="true">&rarr;</span></a></li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>
