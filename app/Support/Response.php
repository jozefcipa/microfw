<?php

namespace Support;
use Exception;

class Response {

	public static function loadView(View $view) {

		// extract data
		extract($view->getData());

		// load view
		include rtrim(config('VIEWS_DIR'), '/') . '/' . '_header.phtml';
		include $view->getPath();
		include rtrim(config('VIEWS_DIR'), '/') . '/' . '_footer.phtml';
	}

	public static function returnJSON($data) {

		if (is_array($data) || is_object($data)) {
			header('Content-type: application/json');
			echo json_encode($data);
		} else {
			throw new Exception('Provided data cannot be converted to JSON response');
		}
	}
}