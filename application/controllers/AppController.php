<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Class AppController
 */
class AppController extends Controller {

	public function __construct() {
		$this->view_path = ROOT . '/application/views/';
		$this->template = 'default';
	}

}