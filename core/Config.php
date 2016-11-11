<?php
namespace Core;

/**
 * Class Config (singleton)
 */
class Config {

	/**
	 * @var $settings array
	 */
	private $settings = [];

	/**
	 * Attribut qui stocke l'instance unique de Config
	 * @var $_instance Config
	 */
	private static $_instance;

	/**
	 * @return Config
	 */
	public static function get_instance($file) {
		if (is_null(self::$_instance)) {
			self::$_instance = new Config($file);
		}
		return self::$_instance;
	}

	/**
	 * @param $file String Path du fichier de configuration
	 */
	public function __construct($file) {
		$this->settings = require $file;
	}

	/**
	 * Permet de récupérer un élément de configuration s'il existe dans le fichier de configuration
	 * @param $key String
	 * @return string
	 */
	public function get($key) {
		return isset($this->settings[$key]) ? $this->settings[$key] : null;
	}

}