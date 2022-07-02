<html>
<head>
<title>Get cookie back></title>
</head>

<body>
<h2>Cookie type:</h2>

<?php

foreach ($_COOKIE as $name => $value){

	echo "<h1>$name $value</h1>";

}

?>

</body>
</html>
