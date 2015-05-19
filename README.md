# IP-Gatekeeper

The IP-Gatekeeper is a poor man's way to keep unwanted eyes off of a project.  It was developed out of a need to allow client access to an unfinished product while still keeping out the general public.  Rather than granting access through some sort of login mechanism, this tool analyzes the user's IP address to grant or deny access.  While obviously not as secure as a login mechanism, this tool serves its purpose by providing a simply way of keeping out unwanted users, while granting access to a client without the need to remember yet another username and password.


## Getting Started
Adding the IP Gatekeeper to any project is easy.  Simply follow these instructions:

1. Include the necessary gatekeeper file
2. Configure the gatekeeper (see below)
2. Create a new Gatekeeper object
3. Put the Gatekeeper on guard

For example:
```php
<?php
require_once('path/to/gatekeeper/gatekeeper.php');
$gatekeeper = new Gatekeeper();
$gatekeeper->guard('gatekeeper/');
?>
<html>
<body>
	...
</body>
</html>
```
Once setup, all unauthorized visitors will be redirected to a landing page; the URL/path of which is defined by the parameter passed to the `$gatekeeper->guard()` method.


## Authorizing a User
To grant a user access to your project, simply ask them to visit the URL below.  The authorization process can also be configured to require a password before granting access to the user.
```
http://yourdomain.com/path/to/gatekeeper/authorize/
```


## Managing Users
Site administrators can also add/remove authorized users via the URL below.  A password is required to access the admin area.  See configuration details below for more information.
```
http://yourdomain.com/path/to/gatekeeper/admin/
```


## Configuration
The gatekeeper tool can be configured via the `gatekeeper/gatekeeper-config.php` file.  Configuration options are defined below:
```php
$config = array(

	// The URL of the gatekeeper landing page
	'gatekeeper_url' => 'http://yourdomain.com/gatekeeper/',

	// The URL of your site/project
	'project_url' => 'http://yourdomain.com/',

	// User authentication password configuration
	'auth_password_required' => false, // true/false
	'auth_password' => 'auth-password-goes-here',

	// Administration password configuration
	'admin_password' => 'admin-password-goes-here'
);
```


## Future Development
- Move the sample index file to a "demo" folder
- Include additional variables to the gatekeeper configuration array that allow the user to change the content for the various elements of each gatekeeper view (i.e. authorize, admin, and the various parts of each).
