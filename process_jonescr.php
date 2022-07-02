<?php
session_start();
setcookie(favcookie, $_POST[sweets], time() + 60*60*24*7);
$_SESSION[fname] = $_POST[firstname];
$_SESSION[lname] = $_POST[lastname];
$_SESSION[period] = $_POST[topic];

print <<<TOP

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>PHP Preference Setter - Web III Assessment #2</title>

<style>

   body		{
		 font-family:Verdana; background-color:$_POST[bgcol]; foreground-color:$_POST[fgcol]}

   h3		{text-align:center}

</style>

</head>

<body>
<h3>Welcome to our site $_POST[firstname]</h3><br />
<form action="page1_jonescr.php" method="POST">

   <input type="hidden" name="fgcolor" value="$_POST[fgcol]" />
   <input type="hidden" name="bgcolor" value="$_POST[bgcol]" />
<h3>So your interested in the $_SESSION[period] period huh?</h3><br /><br />

<h3>and you like $_POST[sweets] cookies... That explains alot :-)</h3><br />

   <input type="submit" name="submit" value="Continue" /><br />

</form>
TOP;



print <<<BOTTOM


</body>
</html>
BOTTOM;

?>
