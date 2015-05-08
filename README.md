# IP-Gatekeeper

This is a poor man's way to keep unwanted eyes off of a project.  It was developed out of a need to allow client access to an unfinished product while still keeping out the general public.  Rather than granting access through some sort of login mechanism, this tool analyzes the user's IP address to grant or deny access.  While obviously not as secure as a login mechanism, this tool serves its purpose by providing a simply way of keeping out unwanted users, while granting access to a client without the need to remember yet another username and password.


## Getting Started
Adding the IP Gatekeeper to any project is easy.  Simply follow these instructions:
1 Include the necessary file
2 Create a new Gatekeeper object
3 Put the Gatekeeper on guard

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
To grant a user access to your project, simply ask them to visit the following URL:
```
http://www.yourdomain.com/path/to/gatekeeper/authorize/
```
Note: the authorization process can also be configured to require that the user provide a password before being granted access.


## Managing Users
Site administrators can add/remove authorized users via the following URL:
```
http://www.yourdomain.com/path/to/gatekeeper/admin/
```
Note: a password is required to access the admin area.


## Configuration
> STILL WORKING HERE
> show and describe all configuration options
```php
$config = array(
	'project_url' => 'http://localhost/_projects/ip-gatekeeper/',
	'gatekeeper_url' => 'http://localhost/_projects/ip-gatekeeper/gatekeeper/',
	'auth_password_required' => true,
	'auth_password' => 'password',
	'admin_password' => 'password'
);
```


## Notes to Self
- Move sample index file to a "demo" folder
- Create a gatekeeper/config.php file to store the configuration array
- Include additional variables to the config array that allow the user to change the content for the various elements of each gatekeeper view (i.e. authorize, admin, and the various parts of each).
