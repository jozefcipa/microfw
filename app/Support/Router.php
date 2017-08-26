<?php

namespace Support;
use Exception;
use Controllers;

class Router {

	private $routes;

	/**
	 * Initialize empty array of routes
	 */
	public function __contstruct() {
		$this->routes = [
			"GET"  => [],
			"POST" => []
		];
	}

	/**
	 * Add GET route
	 *
	 * @param $uri
	 * @param $callback
	 */
	function get($uri, $callback)
	{
		$this->routes["GET"][$uri] = $callback;
	}

	/**
	 * Add POST route
	 *
	 * @param $uri
	 * @param $callback
	 */
	function post($uri, $callback) {
		$this->routes["POST"][$uri] = $callback;
	}

	/**
	 * Redirects by URL
	 */
	function start() {

		$url = $this->getUrl();

		if (($parsedUri = $this->parseUri($url)) !== false) {

			// select values from array
			extract($parsedUri);

			// callback 
			if (is_callable($this->routes[$method][$path])) {

				$response = call_user_func($this->routes[$method][$path], ...array_values($params));
			} 
			// controller
			elseif (strpos($this->routes[$parsedUri['method']][$path], '@') !== false) {

				list($controller, $method) = explode('@', $this->routes[$method][$path]);

				$controller = 'Controllers\\' . $controller; // add namespace
				$controllerObj = new $controller();

				$controllerObj->before(); // before middleware
				$response = $controllerObj->$method(...$parsedUri['params']);
				$controllerObj->after(); // after middleware
			}

			if (! $response) {
				throw new Exception('Method must returns view or data');
			}

			if ($response instanceof View) {
				Response::loadView($response);
			} else {
				Response::returnJSON($response);
			}

		} else {

			// 404 - Not found
			redirect('/');
		}

	}

	/**
	 * Return URL address
	 *
	 * @return string
	 */
	private function getUrl()
	{

		$protocol = array_key_exists("HTTPS", $_SERVER) ? "https://" : "http://";

		//get full URL
		$url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		// remove base url part
		$url = str_replace(config("BASE_URL"), "", $url);

		return $url;
	}

	/**
	 * Return request method and parameters from URL
	 *
	 * @param $url
	 * @return array|bool
	 */
	private function parseUri($uri)
	{

		$method = $_SERVER["REQUEST_METHOD"];

		$route = $this->getRouteFromUrl($uri, $method);

		if ($route == false) {
			return false;
		}

		$params = $this->parseRouteParams($uri, $route);

		return [
			"method" => $method,
			"path"   => $route,
			"params" => $params
		];
	}

	/**
	 * Return array of key-value pairs from URL template and current URL
	 *
	 * @param $currentUrl URL retrieved from $_SERVER variable
	 * @param $urlTemplate URL stored in $routes
	 * @return array
	 */
	private function parseRouteParams($currentUrl, $urlTemplate)
	{

		$templateSegments = $this->parseUrlToSegments($urlTemplate);
		$urlSegments      = $this->parseUrlToSegments($currentUrl);

		$params = [];
		foreach ($templateSegments as $key => $segment) {
			if ($this->isParameterSegment($segment)) {
				$params[ltrim($segment, ":")] = $urlSegments[$key];
			}
		}

		return $params;
	}

	/**
	 * Return segments from splitted URL
	 *
	 * @param $urlPath
	 * @return array
	 */
	private function parseUrlToSegments($urlPath)
	{
		return explode("/", trim($urlPath, "/"));
	}

	/**
	 * Check if $segment matches :something
	 *
	 * @param $segment
	 * @return bool
	 */
	private function isParameterSegment($segment)
	{
		return preg_match("/:[a-z]*/", $segment);
	}

	/**
	 * Try to find right route from URL, because route may contains :variables
	 *
	 * @param $url
	 * @param $method
	 * @return bool|int|string
	 */
	public function getRouteFromUrl($url, $method)
	{
		foreach($this->routes[$method] as $templateURL => $c){

			$templateURLSegments = $this->parseUrlToSegments($templateURL);
			$URLSegments = $this->parseUrlToSegments($url);

			if(count($templateURLSegments) != count($URLSegments))
				continue;

			//remove :variable values from both of arrays
			foreach($templateURLSegments as $key => $segment){
				if($this->isParameterSegment($segment)){
					unset($templateURLSegments[$key]);
					unset($URLSegments[$key]);
				}
			}

			if($templateURLSegments === $URLSegments) {
				return $templateURL;
			}
		}

		return false;
	}
}