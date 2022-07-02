<?php

print <<<PARTONE

<?xml version="1.0"?>


<!CTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>PHP Assessment #1 - Cassidy Jones</title>

<style>


</style>

</head>

<body>

PARTONE;

$loginid = substr($_POST[lastname],0,5);
$first_initial = substr($_POST[firstname],0,1);
$mid_initial = $_POST[mi];
$loginid = $loginid.$first_initial.$mid_initial;
$loginid = strtolower($loginid);
$length = strlen($loginid);


print <<<PARTTWO
<h2>Your new <span style='color:green'>$length</span>-charticter username is <span style='color:navy'>$loginid</span></h2> 
</body>
</html>
PARTTWO;

?>

