<?php
namespace Core;

/**
 * Class Controller
 */
class Controller {

	/**
	 * @var string Chemin du répertoire contenant les vues
	 */
	protected $view_path;

	/**
	 * @var string Nom du template général utilisé
	 */
	protected $template;

	/**
	 * Permet d'afficher une vue à partir d''un controller
	 * @param $view string
	 * @param $variables array
	 */
	protected function render($view, $variables = []) {
		ob_start();
		extract($variables);
		require ($this->view_path . str_replace('.', '/', $view) . '.php');
		$content = ob_get_clean();
		require ($this->view_path . '/templates/' . $this->template . '.php');
	}

	/**
	 * Renvoie une erreur 403
	 */
	protected function forbidden() {
		header('HTTP/1.0 403 Forbidden');
		die('Accès interdit');
	}

	/**
	 * Renvoie une erreur 404
	 */
	protected function not_found() {
		header('HTTP/1.0 404 Forbidden');
		die('Page introuvable');
	}
	
}