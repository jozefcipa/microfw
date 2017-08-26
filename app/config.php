<?php

// errors 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// timezone
date_default_timezone_set('Europe/Bratislava');

// encoding 
mb_internal_encoding('utf-8');

// autoloader 
spl_autoload_register(function ($className) {

	$classPath = str_replace('\\', '/', $className);

	require __DIR__ . '/' . $classPath . '.php';
});

set_exception_handler(function(Throwable $e) {

	echo '<div style="background: crimson; color: #f9f9f9; font-family: monospace; padding: 5px 10px; border-radius: 4px">';
	echo '<h3>' . $e->getMessage() . '</h3>';
	echo '<h4>File: ' . $e->getFile() . ': ' . $e->getLine() . '</h4>';
	echo '<h4>Stack Trace</h4>';
	
	foreach ($e->getTrace() as $key => $t) {
		echo '<div>' . ($key + 1) . '. ' . @$t['file'] . ': ' . @$t['line'] . '</div>';
	}
	echo '</div>';
});

// enable session 
if (!session_id()) {
	session_start();
}

global $config;

$config = [

	'BASE_URL' 		=> 'http://localhost/microfw',
	'VIEWS_DIR'		=> __DIR__ . '/../views',
	'UPLOAD'		=> [
		'IMAGES'    => './public/images',
		'THUMBS'	=> './public/images/thumbs',
		'FILES'		=> './public/files'
	],
	'meta' => [
		'author' 	  => 'https://jozefcipa.com',
		'keywords' 	  => '',
		'websiteName' => 'MicroFW',
		'title'		  => 'When Laravel is overkill and "from scratch" is for nerds',
		'description' => 'Description'
	],
	'DB' => [
		'HOST' 		=> '',
		'NAME' 		=> '',
		'DB_NAME' 	=> '',
		'PASSWORD' 	=> '',
		'PORT' 		=> 3306
	],
	'imageThumb' => [
		'width'  => 69,
		'height' => 69,
		'enable' => true
	]

];

// connect to DB
// Support\DB::connect(config('DB'));