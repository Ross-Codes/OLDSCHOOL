<?php
session_start();



if (isset($_POST[clickit])){

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
  // query our database users table
  $resultid = @mysql_query("SELECT * FROM users");

  if (!$resultid){
     exit('<p>error retrieving user entries from database!<br /> Error: ' . mysql_error() . '</p>');
  }
  $auth = false; //flag used to end loop
  while ((!$auth) && ($row = mysql_fetch_array($resultid))){
     //print "uname = $row[uname] and password = $row[pword]<br />";
     if(($_POST[uname] == $row[uname]) && (md5($_POST[pword]) == $row[pword])){
        //print "<br />We found a match!<br />";
        $auth = true; // we found a match!
     }
     
  }//end while
  if($auth){   // user was authenticated
    $_SESSION[authuser] = $_POST[uname];
    //redirect authenticated user either to admin page or 
    //to page they came from
    if(isset($_GET[url])){
       $url = $_GET[url];
    }
    else{
       $url = "admin.php";
    }
    header("Location: $url");   // redirect user to appropriate url
  }
  else{   //user was not authenticated
     print"<h3>Invaled Account - Please try again</h3>\n\n";
  }
}//end if

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

TOP;

//unset($_SESSION[authuser]);

print <<<BOTTOM

<h2>Please login to become an andministrative user</h2>

<form action="$PHP_SELF" method="POST">
   Username:
   <input type="text" name="uname" /><br /><br />

   Password:
   <input type="password" name="pword" /><br /><br />

   <input type="submit" name="clickit" value="Log In" />
</form>

</body>
</html>
BOTTOM;

?>
