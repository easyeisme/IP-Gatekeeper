<?php
class Gatekeeper {

	private $ip; // user IP address
	private $fh; // file handler
	private $authorized_ip_file = '/authorized-ip.txt'; // relative to class; to be finalized in constructor
	private $admin_password = 'password'; // admin panel password
	private $auth_password = ''; // authorization password
	private $auth_password_required = false;
	private $project_url = ''; // URL of the project / site
	private $gatekeeper_url = ''; // URL of the gatekeeper landing page

	/**
	 * Class constructor.
	*/
	public function __construct() {
		if(session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->authorized_ip_file = __DIR__.$this->authorized_ip_file;
	}

	/**
	 * Gets the URL of the project or site.
	 *
	 * @return string
	*/
	public function getProjectURL() {
		return $this->project_url;
	}

	/**
	 * Sets/overwrites class parameters.
	 * Allow a user to update the value of any parameter within the class.
	 *
	 * @param array $c - an array of key/value pairs; keys must match class parameters for their values to be stored
	*/
	public function setParams($c) {
		foreach(get_object_vars($this) as $key => $val) {
			if(array_key_exists($key, $c)) {
				$this->$key = $c[$key];
			}
		}
	}

	/**
	 * Sets/overwrites the authorization password.
	 *
	 * @param string $p - the new password
	*/
	public function setPassword($p) {
		$this->auth_password = $p;
	}

	/**
	 * Enables the gatekeeping functionality.
	 * Guards the page/site against unwanted visitors.
	 *
	 * @param string $url - a URL to direct all unauthorized visitors to
	*/
	public function guard($url) {
		if(!$this->isAuthorizedIP($this->ip)) {
			$this->gatekeeper_url = $url;
			$this->detainUser();
		}
	}

	/**
	 * Determines if a user's IP address has been authorized by checking it
	 * against a list of valid IP addresses.
	 *
	 * @param string $ip - the user's IP address
	 * @return boolean
	*/
	public function isAuthorizedIP($ip = '') {
		if(empty($ip)) { $ip = $this->ip; }
		$is_valid_user = false;
		$this->fh = fopen($this->authorized_ip_file, 'r');
		while($row = fgets($this->fh)) {
			if(trim($row) === $ip) {
				$is_valid_user = true;
				break;
			}
		}
		fclose($this->fh);
		return $is_valid_user;
	}

	/**
	 * Determines if password is required before a user can be authorized.
	 *
	 * @return boolean
	*/
	public function isPasswordRequired() {
		return $this->auth_password_required;
	}

	/**
	 * Determines if the given password is valid.
	 *
	 * @param string $p - the given password
	 * @return boolean
	*/
	public function isValidPassword($p) {
		return ($this->auth_password === $p);
	}

	/**
	 * Authorizes the user by documenting their IP address (assumming it hasn't
	 * already been documented).
	*/
	public function authorizeUser() {
		$this->addIP($this->ip);
	}

	/**
	 * Adds an IP address to the list of valid IP addresses.
	 *
	 * @param string $ip - the IP address
	*/
	public function addIP($ip) {
		if($this->isValidIP($ip)) {
			if(!$this->isAuthorizedIP($ip)) {
				$this->fh = fopen($this->authorized_ip_file, 'a');
				fwrite($this->fh, $ip."\n");
			}
			fclose($this->fh);
		}
	}

	/**
	 * Removes an IP address from the list of valid IP addresses.
	 *
	 * @param string $ip - the IP address
	*/
	public function removeIP($ip) {
		if($this->isValidIP($ip)) {
			$content = file($this->authorized_ip_file);
			foreach($content as $i => $row) {
				if(trim($row) === $ip) {
					unset($content[$i]);
				}
			}
			$content = implode($content);
			file_put_contents($this->authorized_ip_file, $content);
		}
	}

	/**
	 * Retrieves all authorized IP addresses.
	 *
	 * @return array - an array of authorized IP addresses
	*/
	public function getAuthorizedIPs() {
		$ip_list = file($this->authorized_ip_file);
		return $ip_list;
	}

	/**
	 * Determines if the user represents an authorized administrator
	 *
	 * @return boolean
	*/
	public function isAuthorizedAdminUser() {
		if($_SESSION['is_admin_user'] == true) {
			return true;
		}
		return false;
	}

	/**
	 * Authorizes the user as an administrator
	 *
	 * @param string $p - User input for the administrator password
	*/
	public function authorizeAdminUser($p) {
		if($p === $this->admin_password) {
			$_SESSION['is_admin_user'] = true;
		}
	}

	/**
	 * Determines if the given value represents a valid IP address.
	 *
	 * @param string $ip - the IP address
	*/
	private function isValidIP($ip) {
		return filter_var($ip, FILTER_VALIDATE_IP);
	}

	/**
	 * Redirects the user to a defined holding area for all unauthorized users.
	*/
	private function detainUser() {
		header('Location: '.$this->gatekeeper_url);
		exit;
	}
}
?>