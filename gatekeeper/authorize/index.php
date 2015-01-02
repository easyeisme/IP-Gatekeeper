<?php
require_once('../gatekeeper.php');
$gatekeeper = new Gatekeeper();
$is_password_required = $gatekeeper->isPasswordRequired();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>

<div class="wrapper">
	<h1>Authorization</h1>
	<?php if($is_password_required) { ?>
		<form class="form form-password clearfix" action="" method="post">
			<div class="title">Authorize New IP Address:</div>
			<input type="password" class="input-text" name="password" value="" />
			<button class="btn btn-blue" name="submit">Submit</button>
		</form>
	<?php } else { ?>
		<p>
			User authorization complete.<br/>
			<a href="#">Return to Site</a>
		</p>
	<?php } ?>
</div><!-- .wrapper -->

</body>
</html>