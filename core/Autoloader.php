<?php
namespace Core;

/**
 * Class Autoloader
 */
class Autoloader {

	/**
	 * Permet d'enregistrer une fonction en tant qu'implémentation de __autoload()
	 */
	static function register() {
		spl_autoload_register(array(__CLASS__, 'autoload')); 
	}

	/**
	 * Autoload la classe demandée
	 * @param $class_name string
	 */
	static function autoload($class_name) {
		if (strpos($class_name, __NAMESPACE__.'\\') === 0) { // n'autoload que ce qui est dans son namespace
			$class_name = str_replace(__NAMESPACE__.'\\', '', $class_name);
			$class_name = str_replace('\\', '/', $class_name); // pour serveur
			$class_name = ROOT ."/core/".str_replace("html", "HTML", strtolower(substr($class_name, 0,strrpos($class_name, '/')))).substr($class_name, strrpos($class_name, '/'), strlen($class_name));
			require $class_name.'.php';
		} 
	}
}