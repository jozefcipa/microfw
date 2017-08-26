<?php

namespace Support;

class View {

	private $path;
	private $data;

	public function __construct($name, array $data = []) {
		$this->data = $data;

		$this->path = rtrim(config('VIEWS_DIR'), '/') . '/' . $name . '.phtml';
		if (! file_exists($this->path)) {
			throw new Exception('View [' . $name . '] doesn\'t exist');
		}
	}

	public function getPath() {
		return $this->path;
	}

	public function getData() {
		return $this->data;
	}
}