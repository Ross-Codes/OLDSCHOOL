<?php
define("DAYS1", 86400);

setcookie("chocolate","chip", time() + DAYS1);
setcookie("oatmeal","raisin", time() + 60);
setcookie("peanut","butter", time() + DAYS1);

?>

<html>
<head>
<title>Cookie Example</title>
</head>

<body>
<h1>COOKIE MONSTER</h1>
</body>
</html>
