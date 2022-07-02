<?php

session_start();
if (!isset($_SESSION[authuser])) {
   header("Location: http://db.cs.cvtc.edu/~cooleyjc/php/web3/db/login.php?url=" . urlencode($_SERVER["SCRIPT_NAME"]));
}

print <<<TOP

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Quote DB CMS: Manage the Authors List</title>

<style>

   body         {text-align:center}
   h2           {color:navy; font-family:Trebuchet MS, Verdana}
   table        {width:50%}
   td.author    {color:#CCCC99; background-color:#336699;
                 font-family:Trebuchet MS, Verdana; vertical-align:top}
   td.author:first-letter {font-family:Algerian; font-size:200%}
   td.linkstuff {font-family:Verdana; font-size:0.8em; font-weight:600; 
                 text-align:center; vertical-align:bottom}

</style>

</head>

<body>

<h2>Manage Authors</h2>

TOP;

$pwfile = fopen("../mypasswd", "r");
      
$mypwd = rtrim(fgets($pwfile, 1024)); 
	       
fclose($pwfile);

$dbcnx = @mysql_connect('localhost', 'cooleyjc', $mypwd);

if (!$dbcnx) {
   exit('<p>Unable to connect to the database server at this time</p>');
}


if (!@mysql_select_db('coolquotes')) {
   exit('<p>Unable to locate the coolquotes database at this time</p>');
}


$authors = @mysql_query('SELECT id, name FROM author');

if (!$authors) {
   exit('<p>Error retrieving authors from database!<br />Error: ' . mysql_error() . '</p>');
}


echo "<table border='5' cellpadding='4'>\n\n";

while ($author = mysql_fetch_array($authors)) {

   $id = $author['id'];
   $name = htmlspecialchars($author['name']);

   echo "<tr>\n";
   echo "<td class='author'>$name</td>\n";
        
   echo "<td class='linkstuff'>" .
        "<a href='editauthor.php?id=$id&authname=$name'>Edit</a><br />\n" .
        "<a href='deleteauthor.php?id=$id&authname=$name'>Delete</a>\n" .
        "</td>\n";
   echo "</tr>\n\n";

} // end while

echo "</table>\n\n";

print <<<BOTTOM

<p>
<a href="newauthor.php">Add new author</a>
</p>
<p>
<a href="../admin.php">Return to admin page</a>
</p>

</body>
</html>
BOTTOM;

?>
