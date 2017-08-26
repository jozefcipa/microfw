<?php

namespace Support;
use PDO;

class DB {

	protected static $instance;

	protected static $db;
	public static $fetchType = PDO::FETCH_OBJ;

	public static function connect($config) {
		if (! self::$db) {
			self::$db = new PDO("mysql:host={$config['HOST']}:{$config['PORT']};dbname={$config['DB_NAME']};charset=utf8mb4", $config['NAME'], $config['PASSWORD']);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
	}

	static public function getRow($query, $params = []) {
		$stmt = self::execute($query, $params);

		return $stmt->fetch(self::$fetchType);
	}

	static public function getAll($query, $params = []) {
		$stmt = self::execute($query, $params);

		return $stmt->fetchAll(self::$fetchType);
	}

	static public function execute($query, $params = []) {
		$stmt = self::$db->prepare($query);
		$stmt->execute($params);
		return $stmt;
	}
}