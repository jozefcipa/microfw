<?php

namespace Controllers;
use Support\Controller;

class IndexController extends Controller {
	
	public function home() {


		return $this->view('home', [
			'helloMessage' => 'called via controller view method'
		]);

		return view('home', [
			'helloMessage' => 'called via view helper from controller'
		]);
	}
}