<?php
session_start();

$header = <<<HEADER
<?xml version="1.0"?>
 
<!DOCTYPE html PUBLIC
"-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www/w3/org/TR/xhtml/11/DTD/xhtml1-
transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
                
<head>
<title>Battleship</title>
		                       
<style>           

body  		{color:black; font-family:Trebuchet MS, Verdana; font-size:1.0em;
		 text-align:center}
.board  	{width:275px}
.grid		{height:25px; width:25px}
.divstatus	{text-align:center}
.shipstatus	{text-align:center; background-color:lightblue; font-size:0.7em}
td.topstatus	{background-color:beige}

</style>

</head>

<body>
HEADER;

print $header;



if (!isset($_POST[startgame]) && ($_SESSION[turn] == 0)) {   // game not yet started

   $_SESSION[turn]++;

   print <<<GETGOING
   <h2>*** Battleship ***</h2>

   <form action=$PHP_SELF method="post">
      Player 1's name: 
      <input type="text" name="player1" /><br /><br />
      Player 2's name: 
      <input type="text" name="player2" /><br /><br />

      <input type="submit" name="startgame" value="Anchors Away!" />
   </form>
GETGOING;

}
else {    // game is in progress

   // if first time through, get player names, place ships,
   // and set up the board
   if ($_SESSION[turn] == 1) {
      $player1 = $_POST[player1];
      $player2 = $_POST[player2];


      // 
      // Initialization section for ship stuff
      //
 
      $direction = array("N", "S", "E", "W");
      $ships = array("AC" => 5, "BS" => 4, "CR" => 3, "SU" => 3, "DT" => 2);

      // shiphits is a multidimensional array that contains the shiphit count
      // for both players
      $shiphits = array($player1 =>
                           array("AC" => 0, "BS" => 0, "CR" => 0, "SU" => 0, "DT" => 0, "numsunk" => 0),
                        $player2 =>
                           array("AC" => 0, "BS" => 0, "CR" => 0, "SU" => 0, "DT" => 0, "numsunk" => 0));
      
      $shipcode = array("AC" => "Carrier", "BS" => "Battleship", "CR" => "Cruiser", "SU" => "Submarine", "DT" => "Destroyer", "numsunk" => "Ships Sunk");

      // End ship stuff Initialization


      //
      // Have program randomly place ships on the board for each player
      //
      $boardone = place_ships($player1, $direction, $ships);
      $boardtwo = place_ships($player2, $direction, $ships);

   }  // end if
   else {        // not first turn so...

      // Restore Session variables to shorter, descriptively named variable names
      $player1 = $_SESSION[playerone];
      $player2 = $_SESSION[playertwo];
      $boardone = $_SESSION[board1];
      $boardtwo = $_SESSION[board2];
      $shiphits = $_SESSION[shiphits];
      $ships = $_SESSION[ships];
      $shipcode = $_SESSION[shipcode];

   }  // end else


   //
   // If fired upon, check fired-upon coordinate and determine result
   // (either hit or miss).  Update the user's board and shiphits status.
   //
   if (isset($_POST[fireonit])) {

      if (($_SESSION[turn] % 2) != 0) {   // odd turn number - player 1's turn
         $boardone = firecontrol($player2, $boardone, $ships);
      }
      else {                              // even turn number - player 2's turn
         $boardtwo = firecontrol($player1, $boardtwo, $ships);
      }

   }


   //
   // check to see if we have a victor
   //
   if ($_SESSION[turn] == 0) { 

      unset($_POST[startgame]);

      print <<<NEWGAME

      <form action="$PHP_SELF" method="post">

         <input type="submit" name="playagain" id="playgain" value="Play Again?" />

      </form>

NEWGAME;

   }
   else {   // game is not over ===> proceed

      //print "\n\n<h2>Turn $_SESSION[turn]</h2>\n\n";

      if (($_SESSION[turn] % 2) != 0) {   // odd turn number - player 1's turn
         firesetup($player1, $player2);
      }
      else {                              // even turn number - player 2's turn
         firesetup($player2, $player1);
      }



      print "\n\n<table>\n\n";
      print "<tr>\n";
      print "<td>\n";

      build_board($player1, $boardone);
   
      print "</td>\n";
      print "<td rowspan='14' width='50'>\n";
      print "&nbsp;\n";
      print "</td>\n";
      print "<td>\n";

      build_board($player2, $boardtwo);

      print "</td>\n";
      print "</tr>\n";
   
      print "<tr>\n";
      print "<td>\n";

      ship_status($player1, $player2, $shiphits, $ships, $shipcode);

      print "</td>\n";
      print "<td>\n";

      ship_status($player2, $player1, $shiphits, $ships, $shipcode);

      print "</td>\n";
      print "</tr>\n";

      print "\n</table>\n\n";
      print "</form>\n\n";


      // update all Session variables
      $_SESSION[turn]++;   // update turn number

      $_SESSION[playerone] = $player1;
      $_SESSION[playertwo] = $player2;
      $_SESSION[board1] = $boardone;
      $_SESSION[board2] = $boardtwo;
      $_SESSION[ships] = $ships;
      $_SESSION[shipcode] = $shipcode;
      $_SESSION[shiphits] = $shiphits;

   }  // end else


}  // end else






function place_ships($playername, $direction, $ships) {

   for ($row = 1; $row <= 10; $row++) {
      for ($col = 1; $col <= 10; $col++) {
         $board[$row][$col] = ".";
      }
   }


   foreach ($ships as $shiptype => $shiplength) {

      $placeit = false;   // reset $placeit to false for next ship

      $i = 1;
      while ($placeit == false && $i <= 50) {

	 //echo "<br/>Attempting to place the <strong>$shiptype</strong><br>\n";
         $row = rand(1,10);   // get random row value from 1 to 10
         $col = rand(1,10);   // get random column value from 1 to 10

	 $bearing = $direction[rand(0,3)];  // get random ship bearing (direction)

         switch ($bearing) {

            case "N":
	       //print "bearing is North<br/>\n";
	       $x_axis = 0;
	       $y_axis = -1;
	       if ($row - $shiplength > 0) {
	          $placeit = true;
	       }
	       break;

            case "S":
	       //print "bearing is South<br/>\n";
	       $x_axis = 0;
	       $y_axis = 1;
	       if ($row + $shiplength < 11) {
	          $placeit = true;
	       }
	       break;

            case "E":
	       //print "bearing is East<br/>\n";
	       $x_axis = 1;
	       $y_axis = 0;
	       if ($col + $shiplength < 11) {
	          $placeit = true;
	       }
	       break;

            case "W":
	       //print "bearing is West<br/>\n";
	       $x_axis = -1;
	       $y_axis = 0;
	       if ($col - $shiplength > 0) {
	          $placeit = true;
	       }

	 }  // end switch


	 if ($placeit) {    // place the ship after we check for collisions

	    $testrow = $row;
	    $testcol = $col;
            $ctr = 1;

            while ($placeit && $ctr <= $shiplength) {

               if ($board[$testrow][$testcol] != ".") {
	          $placeit = false;
	       }

	       $testrow = $testrow + $y_axis;
	       $testcol = $testcol + $x_axis;
	       $ctr++;

	    }  // end while

	 }  // end if


	 if ($placeit) {    // OK, if no collisions were found, place the ship

            for ($ctr = 1; $ctr <= $shiplength; $ctr++) {
	       
	       // assign shiptype code to this cell in the board
               $board[$row][$col] = $shiptype;
               //$board[$row][$col] = $shipcode[$shiptype];

	       $row = $row + $y_axis;
	       $col = $col + $x_axis;
	    
	    }  // end for
	 
	 }  // end if

         $i++;
      }  // end while

   }  // end foreach

   display_table($board, $playername);

   return $board;

}   // end function place_ships






function firesetup($first, $second) {

      print <<<FIREFORM

      <form name="fireaway" action="$PHP_SELF" method="post">
         $first, click the coordinate you'd like to fire at on $second's board and then:
         <input type="submit" name="fire" value="Fire!" />
	 <br /><br />

FIREFORM;

}   // end function firesetup





function build_board($playername, $board) {

   //print "\n\n<form action=\"$PHP_SELF\" method=\"post\">\n";
   print "\n\n<table class='board'>\n";
   print "<tr>\n";
   print "<th colspan='11'>$playername's Board</th>\n";
   print "</tr>\n\n";

   print "<tr>\n";
   print "<td class='grid'>&nbsp;</td>\n";

   for ($cell = 1; $cell <= 10; $cell++) {

      print "<td class='grid'>$cell</td>\n";

   }
   print "</tr>\n";  // end first row of table - column coordinates

   // outer loop to produce rows of the table
   for ($row = 1; $row <= 10; $row++) {
   
      print "\n<tr>\n";
      // place letter row coordinate in first cell of each row
      $rowcoord = chr($row + 64);
      print "<td class='grid'>$rowcoord</td>\n";

      // inner loop to produce columns of the table
      for ($col = 1; $col <= 10; $col++) {

	 /*
         if (!$firedon) {
	    $coord = $rowcoord . $col;
	    print "<input type='checkbox' name='fireonit' value=\"$coord\" />\n";
	 }
	 */

	 switch ($board[$row][$col]) {

            case "Miss":
               
               print "<td class='grid' background='images/waves.JPG'>";
	       break;

            case "H-AC":
               
               print "<td class='grid' background='images/xxcarrier.JPG'>";
	       break;

            case "H-BS":

               print "<td class='grid' background='images/xxbattleship.JPG'>";
	       break;

            case "H-CR":

               print "<td class='grid' background='images/xxcruiser.JPG'>";
	       break;

            case "H-SU":

               print "<td class='grid' background='images/xxsubmarine.JPG'>";
	       break;

            case "H-DT":

               print "<td class='grid' background='images/xxdestroyer.JPG'>";
	       break;

	    default:         // it has not been fired on yet - place a checkbox
               print "<td class='grid' background='images/waves.JPG'>";
	       $coord = $rowcoord . $col;
	       print "<input type='checkbox' name='fireonit' value=\"$coord\" />\n";

	 }  // end switch

	 print "</td>\n";

      }

      print "</tr>\n\n";

   }

   print "</table>\n\n";

}   // end function build_board





function firecontrol($player, $board, $ships) {

      global $shiphits;

      // get row and column of coordinate that was fired on
      print "\n\n<h3>$player just fired on coordinate $_POST[fireonit]";
      $row = ord(substr($_POST[fireonit],0,1)) - 64;  // convert row letter to digit
      $col = substr($_POST[fireonit], 1);

      if ($board[$row][$col] == ".") {  // it is a miss - kersploosh!
         $board[$row][$col] = "Miss";
	 print " --- kersploosh!</h3>\n\n";
      }
      else {                            // it is a hit - blam!
         print " --- *blam* it's a Hit!</h3>\n\n";

         // use the shiptype abbreviation to update number of hits on that ship
         $shiptype = $board[$row][$col];
	 $shiphits[$player][$shiptype]++;
	 $numhits = $shiphits[$player][$shiptype];
	 //print "shiptype = $shiptype and number of hits = $numhits<br />";
	 $board[$row][$col] = "H-" . $board[$row][$col];

	 // check to see if the ship has been sunk
	 if ($shiphits[$player][$shiptype] == $ships[$shiptype]) {

	    print "<h3>$player just sank your $shiptype!</h3>\n\n";
	    $shiphits[$player]['numsunk']++;

	    if ($shiphits[$player]['numsunk'] == 5) {

               print "<h2>Game Over - $player is the victor!</h2>\n\n";

	       $_SESSION[turn] = 0;      // reset turn number for new game

	    }

	 }

      }  // end else

      return $board;

}   // end function firecontrol






function ship_status($player1, $player2, $shiphits, $ships, $shipcode) {

   print "<br />\n";
   print "<div class='divstatus'>\n\n";
   print "<table border class='shipstatus'>\n\n";

   print "<tr>\n";
   print "<td colspan='2' class='topstatus'>Status of $player2's ships</td>\n";
   print "</tr>\n\n";

   foreach ($shiphits[$player1] as $shiptype => $numhits) {

      print "<tr>\n";
      print "<td>$shipcode[$shiptype]</td>\n";

      print "<td>";
      if ($shiptype == "numsunk") {
	 $numsunk = $shiphits[$player1]["numsunk"];
         print $numsunk;
      }
      elseif ($numhits == $ships[$shiptype]) {
	 print "Sunk!";
      }
      else {
         print "$numhits hits";
      }

      print "</td>\n";
      print "</tr>\n\n";

   }  // end foreach

   print "</table>\n\n";
   print "</div>\n\n";

}   // end function ship_status






function display_table($board, $playername) {

   print "<h3>$playername's ship placement board</h3>\n\n";
   print "<table border='1'>\n";

   // outer loop to produce rows of the table
   for ($row = 1; $row <= 10; $row++) {
   
      print "<tr>\n";

      // inner loop to produce columns of the table
      for ($col = 1; $col <= 10; $col++) {

         print "<td>\n";

         print $board[$row][$col];

	 print "</td>\n";

      }

      print "</tr>\n\n";

   }

   print "</table>\n\n";

}  // end function display_table






print <<<FOOTER

</body>
</html>
FOOTER;

?>
