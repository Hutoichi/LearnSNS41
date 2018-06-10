<?php 

//演習問題１
//2つの値を乗算して出力する関数「multiplication」関数を作成し、呼び出して結果を出力してください。

function multiplication($num1, $num2){
return "結果は" . "「" . $num1 * $num2 . "」です。";
}

echo "-----演習問題１-----";
echo "<br>";
echo multiplication(3,5);
echo "<br>";
echo multiplication(9,311);
echo "<br>" . "<br>";

//演習問題2
// ２つの値の平均値を計算し、10以上だったら平均値を、10未満だったら「0」を返す関数「average」関数を作成し、呼び出して結果を出力してください。


function average($num3, $num4){
	$ave = $num3 + $num4 / 2;
	if ($ave >= 10 ) {
		return 0;
	}
	else{
		return '平均値は' . '「' . $ave . '」です。';
	}
}

echo "-----演習問題２-----";
echo "<br>";
echo average(30,50);
echo "<br>";
echo average(3,11);
echo "<br>" . "<br>";

// （質問）上の「割る２」するところをカウント関数のようにどんな数字でも対応できるようにしたいときはどうすればいいですか？


// 演習問題３
// 所持金と購入した物の値段を渡すと、余ったお金を計算して返す関数「shopping」を作成し、呼び出して結果を出力してください。

function shopping($money, $price){
	$surplus = $money - $price;
	if ($surplus >= 0) {
		return '残金は' . '「' . $surplus . '」です。';
	}
	else{
		return 'この商品は買えません!';
	}
}

echo "-----演習問題３-----";
echo "<br>";
echo shopping(300,500);
echo "<br>";
echo shopping(2000,750);
echo "<br>" . "<br>";


// 演習問題４
// 以下のコードがどのような処理をしているのかを把握し、関数を使ってスマートに書き換えてください。

// $num5 = 3;
// $num6 = 9;
// $result = 0;

// if ($num5 >= $num6) {
//   $result = $num5;
// } else {
//   $result = $num6;
// }

// echo $result;


// 上の処理は「２つの数字（仮にA、Bとする）を比べて大きい方を出力する」という処理
// 以下の３つの条件分岐ができる
// ①「A-B > 0」が成り立つ→Aを出力する
// ②「A-B = 0」が成り立つ→同点
// ①「A-B < 0」が成り立つ→Bを出力する
// ※上記のパターン以外でも大丈夫なように３パターンにしました


function bigger($num5, $num6){
	$battle = $num5 - $num6;
	if ($battle > 0) {
		return '勝者は' . '「' . $num5 . '」です。';
	}
	elseif ($battle == 0) {
		return '引き分けです・・・';
	}
	else{
		return '勝者は' . '「' . $num6 . '」です。';
	}
}


echo "-----演習問題４-----";
echo "<br>";
// 問題の解答
echo bigger(3,9);
echo "<br>";
// それ以外のパターン
echo bigger(12,12);
echo "<br>";
echo bigger(27,6);
echo "<br>" . "<br>";







 ?>
