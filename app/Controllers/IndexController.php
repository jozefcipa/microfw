<?php

namespace Controllers;
use Support\Controller;

class IndexController extends Controller {

	public function before() {
		// before middleware
	}
	
	public function home($paramFromUrl) {

		// handling code


		// set HTML template
		return view('home', [
			'paramToView' => 'value'
		]);
	}

	public function after() {
		// after middleware
	}
}