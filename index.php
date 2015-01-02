<?php
require_once('gatekeeper/gatekeeper.php');
$gatekeeper = new Gatekeeper();
$gatekeeper->guard();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="gatekeeper/style.css" />
</head>
<body>

<div class="wrapper">
	<h1>Welcome</h1>
	<p>This view represents your website or web project.</p>
</div><!-- .wrapper -->

</body>
</html>