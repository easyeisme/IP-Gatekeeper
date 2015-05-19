<?php
require_once('router.php');
require_once('gatekeeper.php');

// Configuration / Setup
$router = new Router();
$gatekeeper = new Gatekeeper();
require_once('gatekeeper-config.php'); // $config
$config['route'] = trim($router->getRoute(), '/');
$gatekeeper->setParams($config);

// Retrieve user input
$input = array();
foreach($_POST as $key => $val) {
	$input[$key] = htmlentities(stripslashes(trim($val)), ENT_QUOTES);
}

// Process Request:  Authorization
if($config['route'] == 'authorize') {
	if(!$gatekeeper->isAuthorizedIP()) {
		if($gatekeeper->isPasswordRequired()) {
			if($gatekeeper->isValidPassword($input['password'])) {
				$gatekeeper->authorizeUser();
			}
		} else {
			$gatekeeper->authorizeUser();
		}
	}
}

// Process Request:  Admin
if($config['route'] == 'admin') {
	// Login
	if($input['action'] == 'login') {
		$gatekeeper->authorizeAdminUser($input['password']);
	}
	// Add User IP
	if($input['action'] == 'add') {
		$gatekeeper->addIP($input['ip']);
	}
	// Delete User IP
	if($input['action'] == 'delete') {
		$gatekeeper->removeIP($input['ip']);
	}
}
?>
<html>
<head>
<title>Gatekeeper</title>
<link rel="stylesheet" type="text/css" href="<?=$config['gatekeeper_url']?>/style.css" />
</head>
<body>

<div class="wrapper">



	<?php // ===== Page:  Authorize ?>
	<?php // ====================================================================== ?>
	<?php if($config['route'] == 'authorize') { ?>

		<div class="pg-authorize">
			<h1>Authorization</h1>
			<?php if($gatekeeper->isAuthorizedIP()) { ?>
				<p>User authorization complete.</p>
				<?php if($gatekeeper->getProjectURL()) { ?>
					<p><a class="btn btn-gray btn-return" href="<?=$gatekeeper->getProjectURL()?>">Return to Site</a></p>
				<?php } ?>
			<?php } else { ?>
				<?php if($gatekeeper->isPasswordRequired()) { ?>
					<form class="form form-password clearfix" action="" method="post">
						<div class="title">Password:</div>
						<input type="password" class="input-text" name="password" value="" />
						<button class="btn btn-blue" name="submit">Submit</button>
					</form>
					<?php if(isset($_POST['password'])) { ?>
						<div class="error">Password is Incorrect</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div><!-- .pg-authorize -->



	<?php // ===== Page:  Admin ?>
	<?php // ====================================================================== ?>
	<?php } elseif($config['route'] == 'admin') { ?>

		<div class="pg-admin">
			<h1>Manage Authorized Users</h1>
			<?php if(!$gatekeeper->isAuthorizedAdminUser()) { ?>
				<form class="form form-password clearfix" action="" method="post">
					<input type="hidden" name="action" value="login" />
					<div class="title">Password:</div>
					<input type="password" class="input-text" name="password" value="" />
					<button class="btn btn-blue" name="submit">Submit</button>
				</form>
				<?php if(isset($_POST['password'])) { ?>
					<div class="error">Password is Incorrect</div>
				<?php } ?>
			<?php } else { ?>
				<form class="form form-add-ip clearfix" action="" method="post">
					<input type="hidden" name="action" value="add" />
					<div class="title">Authorize New IP Address:</div>
					<input type="text" class="input-text" name="ip" value="" />
					<button class="btn btn-blue" name="submit">Add</button>
				</form>
				<hr/>
				<form class="form form-remove-ip" action="" method="post">
					<input type="hidden" name="action" value="delete" />
					<div class="title">Authorized IP Addresses:</div>
					<ul class="ip-list">
						<?php $authorized_ips = array_reverse($gatekeeper->getAuthorizedIPs()); ?>
						<?php if(count($authorized_ips)) { ?>
							<?php foreach($authorized_ips as $ip) { ?>
								<li class="clearfix">
									<span class="ip-address"><?=$ip?></span>
									<button class="btn btn-gray btn-small" name="ip" value="<?=$ip?>">Remove</button>
								</li>
							<?php } ?>
						<?php } else { ?>
							<div class="empty-list">No authorized users</div>
						<?php } ?>
					</ul><!-- .ip-list -->
				</form>
			<?php } ?>
		</div><!-- .pg-admin -->



	<?php // ===== Page:  Default ?>
	<?php // ====================================================================== ?>
	<?php }	else { ?>

		<div class="pg-gatekeeper">
			<?php if($gatekeeper->isAuthorizedIP()) { ?>
				<h1>Access Granted</h1>
				<p>You are already authorized to view this site.</p>
				<?php if($gatekeeper->getProjectURL()) { ?>
					<p><a class="btn btn-gray btn-return" href="<?=$gatekeeper->getProjectURL()?>">Return to Site</a></p>
				<?php } ?>
			<?php } else { ?>
				<h1>Access Denied</h1>
				<p>You are not authorized to view this site.</p>
			<?php } ?>
		</div><!-- pg-gatekeeper -->



	<?php } ?>



</div><!-- .wrapper -->

</body>
</html>