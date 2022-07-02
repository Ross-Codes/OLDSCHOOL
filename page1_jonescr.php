<?php
session_start();



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
		 font-family:Verdana; background-color:$_POST[bgcolor]; foreground-color:$_POST[fgcolor]}


   h3		{text-align:center}

</style>

</head>

<body>
<h3>Welcome back $_SESSION[fname]</h3> <br />
<form action="page2_jonescr.php" method="POST">
   <input type="hidden" name="fg" value="$_POST[bgcolor]" />
   <input type="hidden" name="bg" value="$_POST[fgcolor]" />
   <p>What nationality is $_SESSION[lname]</p><input type="text" name="nation" value="" /><br />
   <input type="submit" name="submit" value="Find Out More" /> <br />

</form>
<a href="http://www.google.com/search?q=" + $_SESSION[period]>Click here for more info on the $_SESSION[period] period</a> <br />

<h3>Also, isn't your favorite cookie $_COOKIE[favcookie] ?</h3>
TOP;


print <<<BOTTOM


</body>
</html>
BOTTOM;

?>
