
<?php 
	$members = array('A','B','C','D','E');

	$c = count($members);

	for ($i=0; $i < $c; $i++) { 
		echo $members[$i];
	}

	echo "<br>";

	foreach ($members as $member) {
		// $member = members[0];
		echo $member;
	}








 ?>