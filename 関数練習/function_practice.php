<?php 

// 練習問題１

// 「seedくん」という文字列を出力する「nexseed」という名前の関数を作成して見ましょう。（引数はなし）

// function nexseed(){
// 	echo 'seedくん';
// }


// // 練習問題2
// nexseed();


// 練習問題3
// 練習問題2までを参考に「nexseed関数に「greating」」という引数を追加して「▲▲▲、seedくん」と表示されるようにする

// function nexseed($greating){
// 	echo $greating . '、' . 'seedくん';
// }

// nexseed('こんにちは');
// echo "<br>";
// nexseed('おはよう');

// 練習問題4
// 名前も表示

// function nexseed($greating,$name){
// 	echo $greating . '、' . $name . 'さん';
// }

// nexseed('こんにちは','たいち');
// echo "<br>";
// $greating = "御機嫌よう";
// $name = "フィリア";
// nexseed($greating,$name);


// 練習問題5
// 戻り値として使用する
function nexseed($greating,$name){
	return $greating . '、' . $name . 'さん';
}

// 戻り値を出力するときは「echo」が必要
echo nexseed('こんにちは','たいち');
echo "<br>";


// 2つの値の合算値を出す関数
function plus($num1, $num2) {
  $result = $num1 + $num2;
  return $result;
}

// 関数の戻り値をそのまま出力する場合
echo '加算の結果は' . plus(30, 20) . 'です';
echo "<br>";

$sum = plus(30, 20);
echo '合計は' . $sum . 'です';
echo "<br>";

// 変数「$sum」に戻り値を格納して出力する場合
// function plus($num1, $num2) {
//   $result = $num1 + $num2;
//   return $result;
//   echo 'ここは処理されません';
// }

// plus(30, 20);
// echo "<br>";


// 点数をチェックして合否を返す関数
// returnは条件分岐で何回も使用できる
function checkExam($score){
  if ($score > 80) {
    return '合格！';
  } else {
    return '不合格';
  }
}

$result = checkExam(70);
echo $result;
echo "<br>";





 ?>