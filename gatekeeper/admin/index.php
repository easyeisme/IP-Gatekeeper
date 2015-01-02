<?php
require_once('../gatekeeper.php');
$gatekeeper = new Gatekeeper();

// Retrieve user input
$input = array();
foreach($_POST as $key => $val) {
	$input[$key] = htmlentities(stripslashes(trim($val)), ENT_QUOTES);
}

// Handle valid user requests
if(!empty($input['ip'])) {
	// Add IP Address
	if($input['action'] === 'add') {
		$gatekeeper->addIP($input['ip']);
	}
	// Remove IP Address
	if($input['action'] === 'remove') {
		$gatekeeper->removeIP($input['ip']);
	}
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body class="pg-admin">

<div class="wrapper">
	<h1>Manage User Authorization</h1>

	<form class="form form-add-ip clearfix" action="" method="post">
		<input type="hidden" name="action" value="add" />
		<div class="title">Authorize New IP Address:</div>
		<input type="text" class="input-text" name="ip" value="" />
		<button class="btn btn-blue" name="submit">Add</button>
	</form>

	<hr/>

	<form class="form form-remove-ip" action="" method="post">
		<input type="hidden" name="action" value="remove" />
		<div class="title">Authorized IP Addresses:</div>
		<ul class="ip-list">
			<?php $ip_list = array_reverse($gatekeeper->getAuthorizedIPs()); ?>
			<?php if(count($ip_list) > 0) { ?>
				<?php foreach($ip_list as $ip) { ?>
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

</div><!-- .wrapper -->

</body>
</html>