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
<title>Quote DB CMS: Manage the Quotes List</title>

<style>

   body		{text-align:center}
   h2     	{color:navy; font-family:Trebuchet MS, Verdana}
   table	{width:80%}
   td.quote	{color:#CCCC99; background-color:#336699;
   		 font-family:Trebuchet MS, Verdana; vertical-align:top}
   td.quote:first-letter {font-family:Algerian; font-size:200%}
   td.linkstuff	{font-family:Verdana; font-size:0.8em; font-weight:600; 
   		 text-align:center; vertical-align:bottom}
   #author  	{font-weight:600; font-size:0.7em;
                 color:#FFFFCC; font-family:Lucida Handwriting, Comic Sans MS}

</style>

</head>

<body>

<h2>Manage Quotes</h2>

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



$quotes = @mysql_query('SELECT quotes.id, quotetxt, name
                        FROM quotes, author WHERE authorid=author.id');

if (!$quotes) {
   exit('<p>Error retrieving quotes from database!<br />Error: ' . mysql_error() . '</p>');
}


echo "<table border='5' cellpadding='4'>\n\n";

while ($quote = mysql_fetch_array($quotes)) {

   $id = $quote['id'];
   $quotetext = htmlspecialchars($quote['quotetxt']);
   $author = htmlspecialchars($quote['name']);

   echo "<tr>\n";
   echo "<td class='quote'>$quotetext<br />" . 
        "<span id='author'>&nbsp;&nbsp;$author</span><br /><br /></td>\n";
   echo "<td class='linkstuff'>" .
        "<a href='editquote.php?id=$id'>Edit</a><br />\n" .
        "<a href='deletequote.php?id=$id'>Delete</a>\n" .
        "</td>\n";
   echo "</tr>\n\n";

} // end while

echo "</table>\n\n";

print <<<BOTTOM

<p>
<a href="newquote.php">Add new quote</a>
</p>
<p>
<a href="../admin.php">Return to admin page</a>
</p>

</body>
</html>
BOTTOM;

?>
