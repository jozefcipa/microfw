<?php

namespace Support;
use Exception;
use stdClass;

class Controller {

	public function __construct() {
		$this->view = new stdClass;
	}

	public function before() {
		// before middleware
	}

	public function after() {
		// after middleware
	}

	public function view($viewName, array $viewData = []) {
		
		return new View($viewName, $viewData);
	}
}