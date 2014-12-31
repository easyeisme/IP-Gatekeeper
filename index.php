<?php
require_once('gatekeeper/gatekeeper.php');
$gatekeeper = new Gatekeeper();
$gatekeeper->authorizeUser();
$gatekeeper->addIP('0.0.0.0');
$gatekeeper->removeIP('123.123.123.123');
// $gatekeeper->guard();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<h1>Welcome to My Website</h1>

</body>
</html>