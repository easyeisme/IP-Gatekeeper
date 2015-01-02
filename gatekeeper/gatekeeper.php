<?php
class Gatekeeper {

	private $ip;
	private $password = 'booger';
	private $password_required = true;
	private $redirect_url = 'http://localhost/_projects/ip-gatekeeper/unauthorized.html';
	private $authorized_ip_file = '/authorized-ip.txt'; // relative to class; to be finalized in constructor
	private $fh;

	/**
	 * Class constructor.
	*/
	public function __construct() {
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->authorized_ip_file = __DIR__.$this->authorized_ip_file;
	}

	/**
	 * Enables the gatekeeping functionality.
	 * Guards the page/site against unwanted visitors.
	*/
	public function guard() {
		if(!$this->isAuthorizedIP($this->ip)) {
			$this->redirectUser();
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
		return $this->password_required;
	}

	/**
	 * Determines if the given password is valid.
	 *
	 * @param string $p - the given password
	 * @return boolean
	*/
	public function isValidPassword($p) {
		return ($this->password === $p);
	}

	/**
	 * Sets/overwrites the authorization password.
	 *
	 * @param string $p - the new password
	*/
	public function setPassword($p) {
		$this->password = $p;
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
	 * Determines if the given value represents a valid IP address.
	 *
	 * @param string $ip - the IP address
	*/
	private function isValidIP($ip) {
		return filter_var($ip, FILTER_VALIDATE_IP);
	}

	/**
	 * Redirects the user to the defined location.
	*/
	private function redirectUser() {
		header('Location: '.$this->redirect_url);
		exit;
	}
}
?>