<?php
    session_start();
    $errors = array();

        //POST送信があったときに以下を実行する（確認する）
    if (!empty($_POST)) {
        $name = $_POST['input_name'];
        $email = $_POST['input_email'];
        $password = $_POST['input_password'];
        $count = strlen($password);

        // ユーザー名の空チェック
        if ($name == '') {
            $errors['name'] = 'blank';
        }
        // メールアドレスの空チェック
        if ($email == '') {
            $errors['email'] = 'blank';
        }
        // 重複してたらエラー出る
        else{
          // 1.DB接続
          require('../dbconnect.php');

          // 2.SQL検索
          $sql = 'SELECT COUNT(*) as `cnt` FROM `users` WHERE `email`=?';
          $data =  array($email);
          $stmt = $dbh->prepare($sql);
          $stmt->execute($data);

          // 3.DB切断
          $dbh = null;

          // 4.取り出し
          $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rec['cnt'] > 0) {
          $errors['email'] = 'duplicate';
        }

        }
        // ユーザー名の空チェック
        if ($password == '') {
            $errors['password'] = 'blank';
        }
        elseif ($count<4 || $count>16) {
            $errors['password'] = 'length';
        }
        // 画像名を取得
        $file_name = $_FILES['input_img_name']['name'];
        // echo $file_name;
        if (!empty($file_name)) {
          // 拡張子チェック
          // 画像名の後ろから3文字取得
          $file_type = substr($file_name, -3);
          // 大文字が含まれてた場合、すべて小文字化
          $file_type = strtolower($file_type);

          if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'gif' && $file_type != 'peg') {
            $errors['img_name'] = 'type';
          }

        }
        // ファイルがないときの処理
          else{
            $errors['img_name'] = 'blank';
        }

          // エラーがないときの処理
          if (empty($errors)) {
            date_default_timezone_get('Asia/Manila');
            $date_str = date('YmdHis'); //被らないためにdate関数を使用する
            $submit_file_name = $date_str.$file_name;
            echo $date_str;
            echo "<br>";
            echo $submit_file_name;
            move_uploaded_file($_FILES['input_img_name']['tmp_name'], '../user_profile_img/'.$submit_file_name);

            $_SESSION['register']['name'] = $_POST['input_name'];
            $_SESSION['register']['email'] = $_POST['input_email'];
            $_SESSION['register']['password'] = $_POST['input_password'];
            // 上記3つは$_SESSION['register']=$_POST;という書き方で
            // 1文にまとめることもできます
            $_SESSION['register']['img_name'] = $submit_file_name;

            header('Location: check.php');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
   <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <!-- ここにコンテンツ -->
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">

          <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎">
            <!-- エラーの名前が出て、かつそれが空白だったとき -->
            <?php if(isset($errors['name']) && $errors['name'] == 'blank') { ?>
            <p class="text-danger">ユーザー名を入力してください</p>
            <?php } ?>
          </div>

          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if(isset($errors['email']) && $errors['email'] == 'blank') { ?>
            <p class="text-danger">メールアドレスを入力してください</p>
            <?php } ?>
            <!-- メールアドレス重複でエラー -->
            <?php if(isset($errors['email']) && $errors['email'] == 'duplicate') { ?>
            <p class="text-danger">同じメールアドレスは使用できません</p>
            <?php } ?>
          </div>

          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if(isset($errors['password']) && $errors['password'] == 'blank') { ?>
            <p class="text-danger">パスワードを入力してください</p>
            <?php } ?>

            <?php if(isset($errors['password']) && $errors['password'] == 'length') { ?>
            <p class="text-danger">パスワードは4〜16文字で書いてください</p>
            <?php } ?>
          </div>

          <div class="form-group">
            <label for="img_name">プロフィール画像</label>
            <input type="file" name="input_img_name" id="img_name" accept="image/*">
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank') { ?>
            <p class="text-danger">画像を選択してください</p>
            <?php } ?>

            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type') { ?>
            <p class="text-danger">画像の拡張子が「jpg」「png」「gif」「jpeg」の画像を選択してください。
            <?php } ?>
          </div>

          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>
    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
