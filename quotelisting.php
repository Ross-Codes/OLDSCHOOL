<?php

print <<<TOP

<?xml version="1.0"?>

<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Quote Listing Page</title>


<style>
h2{
font-family: Trebuchet MS, Verdana;
color: Navy;
}
#author{
font-weight: 600;
font-size: 0.7em;
color:darkgreen;
font-family: Comic Sans MS;
}
</style>

</head>

<body>


TOP;

// check to see what content we should display at this point
if (isset($_GET[clicked])){
   unset($_GET[clicked]);
   print<<<MYFORM
   <form action="$PHP_SELF" method="POST">
   Enter the quote text:<br />
   <textarea name="newquote" rows="10" cols="40"></textarea><br /><br />
   Enter the author of this quote:<br />
   <input type="text" name="newauthor" /><br /><br />
   <input type="submit" name="submit" value="Quote it" />
   </form>
MYFORM;
}
else{

   print "<h2> My favorite quotes!</h2>";

   print "<h3>Here are all the quotes in our database</h3>";


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
   //If a new quote has been submitted then add it to the database
   //after ensuring that no HTML tags are present in the quote

   if(isset($_POST[newquote] && trim($_POST[newquote]) != ""){
   
      if (trim($_POST[newauthor]) == ""){
         $_POST[newauthor] = "Unknown";
      }
      //check to ensure no html tags are present
      $quote = strip_tags($_POST[newquote]);
      $author = strip_tags($_POST[newauthor]);
      
      //unset($_post[newquote]);
      //unset($_POST[newauthor]);
      $_POST = array();
      //check to see if author is already in author table

      $authorlist = @mysql_query("SELECT id FROM author WHERE name = '$author'");
      if(!authorlist){
         exit("<p>Error with author query</p>\n");
      }
      else{//our query did not fail with an error
          // use result set ID to fetch array of result set rows
          $row = mysql_fetch_array($authorlist);
          if (!$row[id]){//author does not exsist in author table
             $sql = "INSERT INTO author SET name='$author'";
             if(@mysql_query($sql)){
                print "<p>Your new author $author has been added</p>\n\n";
             }//endif
             else{
                exit("<p>Error inserting new author</p>");
             }
             // now we must requery the author table for this new author
             //to get it's id because we need the author id to insert
             //the new quote in the quotes table
             $authorlist = @mysql_query("SELECT id FROM author WHERE name = '$author'");
             $row = mysql_fetch_array($authorlist);
          }//endif
         $id = $row[id];
         //add the new quote from the form to our quotes table
         $sql = "INSERT INTO quotes SET quotetxt='$quote',authorid='$id'";
         if (@mysql_query($sql)){
            print "<p>Your quote has been added</p>\n\n";
         }
         else{
            print "<p>Error submitted quote:" . mysql_error() . "</p>\n\n";
         }
      }//endelse
   }

   $resultid = @mysql_query('SELECT quotetxt, name FROM quotes, author WHERE quotes.authorid = author.id');

   if (!$resultid) {
   exit('<p>Error retrieving quotes from database!<br />Error: ' . mysql_error() . '</p>');
   }

   echo "<blockquote>\n\n";

   //step through result set one row at a time
   while($row = mysql_fetch_array($resultid)){

      echo "<p>" . $row['quotetxt'] . "<br />\n\n";
      echo "<span id='author'>" . $row['name'] . "</span></p>\n\n";

   }

   echo "</blockquote>\n\n";

   echo "<a href='$PHP_SELF?clicked=1'>Add a quote!</a>\n\n";
}//end else
print <<<BOTTOM

</body>
</html>
BOTTOM;

?>
