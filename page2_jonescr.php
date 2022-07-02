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

   body         {
                 font-family:Verdana; background-color:$_POST[bg]; foreground-color:$_POST[fg]}

   h3           {text-align:center}

</style>

</head>

<body>
<h3>Welcome back $_SESSION[fname] $_SESSION[lname]</h3>
<form action="page2_jonescr.php" method="POST">
   <input type="hidden" name="fg" value="$_POST[bgcolor]" />
   <input type="hidden" name="bg" value="$_POST[fgcolor]" />
  
</form>
<h3>So you are from $_POST[nation] oragin!</h3><br />
<h3>Sounds like a facinating background</h3><br />
<h3><a href="http://www.google.com/search?q=" + $_POST[nation]>Click here for more info about $_POST[nation] </a></h3><br />

<h3>PS: Your new favorite cookie is a $_COOKIE[favcookie] :-)</h3><br />
TOP;


print <<<BOTTOM


</body>
</html>
BOTTOM;

?>

