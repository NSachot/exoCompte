<?php
use Core\Config;

/**
 * Class App (singleton)
 */
class App {

	/**
	 * Attribut qui stocke l'instance unique de App
	 * @var $_instance App
	 */
	private static $_instance;

	/**
	 * @var $database Base de données utilisée par l'application
	 */
	private $db_instance;

	/**
	 * @return App
	 */
	public static function get_instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new App();
		}
		return self::$_instance;
	}

	/**
	 * Initialise les autoloader nécessaires
	 */
	public static function load() {
		session_start();
		require ROOT.'/application/Autoloader.php';
		App\Autoloader::register();
		require ROOT.'/core/Autoloader.php';
		Core\Autoloader::register();
	}

	/**
	 * @return $database
	 */
	public function get_db() {
		$config = Config::get_instance(dirname(__DIR__).'/config/config.php');
		if ($this->db_instance === null) {
			$this->db_instance = new Core\Database(
				$config->get('db_type'), 
				$config->get('db_name'), 
				$config->get('db_user'), 
				$config->get('db_pass'), 
				$config->get('db_host')
				);
		}
		return $this->db_instance;
	}
}