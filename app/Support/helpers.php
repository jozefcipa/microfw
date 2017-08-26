<?php

function asset($file) {
	//.js extension
	if(strpos($file, "js") != false){
		echo "<script src='" . config('BASE_URL') . "/public/js/$file'></script>" . PHP_EOL;
	}
	//.css extension
	else if(strpos($file, "css") != false){
		echo "<link rel='stylesheet' href=\"" . config('BASE_URL') . "/public/css/$file\"/>" . PHP_EOL;
	}
}

function urlContains($uri) {
	return strpos($_SERVER['REQUEST_URI'], $uri);
}

function flash($name, $msg) {
	$_SESSION[$name][] = $msg;
}

function getFlashMessages($name) {
	if (array_key_exists($name, $_SESSION)) {

		// select messages 
		$messages = $_SESSION[$name];

		// delete from session
		unset($_SESSION[$name]);

		return $messages;
	}

	return '';
}

function redirect($url) {
	header("Location: ". config("BASE_URL") .ltrim($url, "/"));
	exit;
}

function dump() {

	echo '<pre style="color:magenta; background:#333; padding: 20px 10px;">';
	foreach (func_get_args() as $arg) {
		var_dump($arg);
	}
	echo '</pre>';
}

function dd() {
	dump(...func_get_args());
	exit;
}

function config($name) {
	global $config;

	if (strpos($name, '.') === false) {
		if (array_key_exists($name, $config)) {
			return $config[$name];
		} else {
			throw new \Exception('Value for key "' . $name . '" doesn\'t exist in config');
		}
	} else {
		// parse main and sub category
		list($main, $sub) = explode('.', $name);

		if (array_key_exists($main, $config) && array_key_exists($sub, $config[$main])) {
			return $config[$main][$sub];
		} else {
			throw new \Exception('Value for key "' . $main . '.' . $sub . '" doesn\'t exist in config'); 
		}
	}
}

function url($uri) {
	return config('BASE_URL') . '/' . ltrim($uri, "/");
}

function view($viewName, array $data = []) {
	return new Support\View($viewName, $data);
}