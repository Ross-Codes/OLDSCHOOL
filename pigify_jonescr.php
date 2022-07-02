<?php
print <<<NOW

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>PHP pigify</title>

<style>

</style>

</head>

<body>
NOW;
print <<<THEPEN
<form name="pigify" method="POST">
<textarea name="comment" rows="20" columns="40">

</textarea>
<input type="submit" name="pigify" value="pigify" />
</form>
THEPEN;

$words = $_POST[pigify];

strtolower($words);
$swords = explode(" ", $words);
foreach($swords as $a){
	trim($a);
	$firstletter = substr($a, 0, 1);
	$restofword = substr($a, 1, strlen($a) -1);
	if($firstletter == strstr("aeiou", $a){
	$b = $firstletter.$restofword.way;
	
	}
        
	else{
	$b = $restofword.$fistletter.ay;
	}
$newword .= $b;

}
print <<<END
<h3>$newword</h3>



</body>
</html>
END;
?>
