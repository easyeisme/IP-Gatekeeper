<?php
require_once('../gatekeeper.php');
$gatekeeper = new Gatekeeper();

// Retrieve user input
$input = array();
foreach($_POST as $key => $val) {
	$input[$key] = htmlentities(stripslashes(trim($val)), ENT_QUOTES);
}

// Attempt to authorize the user
$password_required = false;
if(!$gatekeeper->isAuthorizedIP()) {
	$password_required = $gatekeeper->isPasswordRequired();
	if($password_required) {
		if($gatekeeper->isValidPassword($input['password'])) {
			$gatekeeper->authorizeUser();
			$password_required = false;
		}
	} else {
		$gatekeeper->authorizeUser();
	}
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body class="pg-authorize">

<div class="wrapper">
	<h1>Authorization</h1>
	<?php if($password_required) { ?>
		<form class="form form-password clearfix" action="" method="post">
			<div class="title">Password:</div>
			<input type="password" class="input-text" name="password" value="" />
			<button class="btn btn-blue" name="submit">Submit</button>
		</form>
		<?php if(!empty($input['password']) && !$gatekeeper->isValidPassword($input['password'])) { ?>
			<div class="error">Password is Incorrect</div>
		<?php } ?>
	<?php } else { ?>
		<p>
			User authorization complete.<br/>
			<a href="#">Return to Site</a>
		</p>
	<?php } ?>
</div><!-- .wrapper -->

</body>
</html>