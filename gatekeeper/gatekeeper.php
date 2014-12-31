<?php
class Gatekeeper {

	private $ip = '';
	private $password = '';
	private $password_required = false;
	private $redirect_url = 'http://localhost/_projects/ip-gatekeeper/redirect-target.html';
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
	 * Determines if a user's IP address has been authorized by checking it
	 * against a list of valid IP addresses.
	 *
	 * @param string $ip - the user's IP address
	 * @return boolean
	*/
	private function isAuthorizedIP($ip) {
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
	 * Determines if the given value represents a valid IP address.
	 *
	 * @param string $ip - the IP address
	*/
	private function isValidIP($ip) {
		return filter_var($ip, FILTER_VALIDATE_IP);
	}



	// Redirect the user
	private function redirectUser() {
		header('Location: '.$this->redirect_url);
		exit;
	}





	/*
	private $db;
	private $userID = 0;
	private $userIDBufferLength = 3; // user for encrypting user IDs in confirmation codes
	private $tzOffset = 0;

	/**
	 * Class constructor.
	 *
	 * @param PDO $db_conn - The database connection handler
	 * @param int $uid - The user ID in the database
	* /
	public function __construct($db_conn, $uid = '') {
		$this->db = $db_conn;
		if(!empty($uid)) {
			$this->userID = $uid;
		} else {
			if(!empty($_SESSION['user-id'])) {
				$this->userID = $_SESSION['user-id'];
				$this->tzOffset = $_SESSION['tz-offset'];
			}
		}
	}
	*/
}
?>