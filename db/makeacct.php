<?php

print <<<TOP

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title></title>

<style>

</style>

</head>

<body>

TOP;

$pwfile = fopen("mypasswd", "r");
$mypwd = rtrim(fgets($pwfile, 1024));
fclose($pwfile);

$dbcnx = @mysql_connect('localhost', 'jonescr', $mypwd);

if (!$dbcnx){
   exit('<p>Unable to connect to the database server at this time.</p>');
}

if (!@mysql_select_db('jonescrquotes')){
   exit('<p>Unable to locate the jonescrquotes database at this time.</p>');
}

$pwfile = fopen("ids_sp2006", "r");

while (!feof($pwfile)){
   $line = rtrim(fgets($pwfile, 1024));
   echo "username is $line ";
   $pass = md5(substr($line, 0 , 4));
   echo "password is $pass<br />\n";

   //
   // set up SQL string to be sent to MYSQL
   //
   $sql = "INSERT INTO users SET uname='$line', pword='$pass'";

   if (@mysql_query($sql)) {
       echo"<h3>Your account has been added to the database</h3>\n\n";
   }
   else {
       echo "<p>Error adding new account to the users table!<br />" . "Error: " . mysql_error() . "</p>\n";
   }



} // end while

fclose($pwfile);

print <<<BOTTOM

</body>
</html>
BOTTOM;

?>
