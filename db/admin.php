<?php
// check to see if the user is authenticated
session_start();

if(!isset($_SESSION[authuser])){  //user not authenticated

   header("Location: http://db.cs.cvtc.edu/~jonescr/web3/login.php?url=" . urlencode($_SERVER["SCRIPT_NAME"]));

}
print <<<TOP

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Quotes CMS Admin Page</title>

<style>

</style>

</head>

<body>
<h2>Quotes site content management system</h2>

<ul>
  <li><a href="quotes/quote_admin.php">manage quotes</a></li>
  <li><a href="quotes/author_admin.php">manage authors</a></li>

</ul>
TOP;
print "<a href='login.php'>Logout</a>\n";

print <<<BOTTOM

</body>
</html>
BOTTOM;

?>
