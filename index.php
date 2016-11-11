<?php

// initialisation
define('ROOT', __DIR__);
require 'application/App.php';
App::load();


// route par défaut
if (isset($_GET['p'])) {
	$p = $_GET['p'];
} else {
	$p = 'login';
}

// routing
$controller = new \App\Controllers\Account\AccountController();
$user = \App\Models\User::user_connected();
if (!$user) {
	if ($p == 'register') {
		$controller->register();
	} else {
		$controller->login();
	}
} elseif ($p == 'account' || $p == 'login') {
	$controller->show($user);
} elseif ($p == 'edit_info') {
	$controller->edit_info($user);
} elseif ($p == 'edit_pass') {
	$controller->edit_pass($user);
} elseif ($p == 'disconnect') {
	$controller->disconnect();
} else {
	require $controller->page_not_found();
}