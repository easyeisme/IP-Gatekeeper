<?php
class Router {

	private $route;

	/**
	 * Class constructor.
	*/
	public function __construct() {
		$this->parseRequest();
	}

	/**
	 * Returns the route
	 *
	 * @return string
	*/
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Parses the URL and extracts the true route from the request.
	*/
	private function parseRequest() {
		// parse the url
		// store the results in $this->route;
		$req = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__));
		$req = str_replace($req, '', $_SERVER['REQUEST_URI']);
		$this->route = $req;
	}
}
?>