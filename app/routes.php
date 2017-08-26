<?php

$router = new Support\Router();

$router->get('/', function() {	
	return view('home');
});