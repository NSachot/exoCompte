<?php

namespace App\Controllers\Account;

use App\Controllers\AppController;
use App\Models\User;
use Core\HTML\Form;
use Core\Validator;

/**
 * Class AccountController
 */
class AccountController extends AppController {

	/**
	 * Gestion de la page de connexion
	 * - Au premier chargement : affiche le formulaire
	 * - A la validation AJAX : réponse en JSON
	 */
	public function login() {

		// Le formulaire a déjà été rempli
		if(!empty($_POST['mail']) && !empty($_POST['password']) ) {
			$mail = $_POST['mail'];
			$password = sha1($_POST['password']);

			$user = \App\Models\User::check($mail, $password);
			$data = array();

			if (!empty($user)) {
            	$_SESSION['id'] = $user->get_id();
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
			echo json_encode($data);

		// Premier chargement du formulaire
		} else {
			$form = new Form();
			$form_edit = '';
			$form_edit .= $form->input('mail', 'Adresse mail', 'email');
			$form_edit .= $form->input('password', 'Mot de passe', 'password');
			$form_edit .= $form->submit();
			$this->render('account.login', compact('form_edit'));
		}
	}

	/**
	 * Gestion de la page d'inscription
	 * - Au premier chargement : affiche le formulaire
	 * - A la validation AJAX : réponse en JSON
	 */
	public function register() {

		// Le formulaire a déjà été rempli
		if(!empty($_POST['mail']) && !empty($_POST['nom']) && !empty($_POST['prenom'])
								  && !empty($_POST['password']) && !empty($_POST['password-confirm'])) {

			// Validation générale
			$validator = new Validator();
			$validator->check_mail($_POST['mail']);
			$validator->check_nom_de_famille($_POST['nom']);
			$validator->check_prenom($_POST['prenom']);
			$validator->check_password_content($_POST['password']);
			$validator->check_password_length($_POST['password']);
			$data['errors'] = $validator->get_errors();

			// Validation métier
			if (\App\Models\User::mail_uniq($_POST['mail']) != false) { 
				$data['errors']['mail']['err_user_mail_01'] = "Cette adresse mail n'est pas disponible";
			}
			if ($_POST['password'] != $_POST['password-confirm']) {
				$data['errors']['password-confirm']['err_pass'] = "Les deux mots de passe ne correspondent pas";
			}

			if (!empty($data['errors'])) {
				$data['success'] = false;
			} else {
				// Enregistrement de l'utilisateur
				$user = new User();
				$user->set_mail($_POST['mail']);
				$user->set_nom($_POST['nom']);
				$user->set_prenom($_POST['prenom']);
				$user->set_password(sha1($_POST['password']));
				$_SESSION['id'] = $user->insert();
				$data['success'] = true;
			}
			echo json_encode($data);

		// Premier chargement du formulaire
		} else {
			$form = new Form(); // sauf password
			$form_edit = '';
			$form_edit .= $form->input('mail', 'Adresse mail', 'email'); // type email ???
			$form_edit .= $form->input('nom', 'Nom');
			$form_edit .= $form->input('prenom', 'Prenom');
			$form_edit .= $form->input('password', 'Mot de passe', 'password');
			$form_edit .= $form->input('password-confirm', 'Confirmer le mot de passe', 'password');
			$form_edit .= $form->submit();
			$this->render('account.register', compact('form_edit', 'errors'));
		}
	}

	/**
	 * Déconnexion de l'utilisateur
	 */
	public function disconnect() {
		session_destroy();
		unset($_SESSION);
		$this->login();
	}

	/**
	 * Affiche la page des informations personnelles de l'utilisateur connecté
	 */
	public function show($user) {
		$data = array(
			'mail' => $user->get_mail(),
			'nom' => $user->get_nom(),
			'prenom' => $user->get_prenom()
		);

		$form = new Form($data);

		$form_edit_info = '';
		$form_edit_info .= $form->input('mail', 'Adresse mail', 'email', true);
		$form_edit_info .= $form->input('nom', 'Nom', 'text', true);
		$form_edit_info .= $form->input('prenom', 'Prenom', 'text', true);
		$form_edit_info .= $form->submit();

		$form_edit_pass = '';
		$form_edit_pass .= $form->input('password', 'Mot de passe', 'password');
		$form_edit_pass .= $form->input('new_password', 'Nouveau mot de passe', 'password');
		$form_edit_pass .= $form->input('new_password_confirm', 'Confirmer', 'password');
		$form_edit_pass .= $form->submit();
		$this->render('account.account', compact('user', 'form_edit_info', 'form_edit_pass'));
	}

	/**
	 * Appelé en AJAX, permet de mettre à jour les informations personnelles de l'utilisateur connecté
	 */
	public function edit_info($user) {

		// Validation générale
		$validator = new Validator();
		$validator->check_mail($_POST['mail']);
		$validator->check_nom_de_famille($_POST['nom']);
		$validator->check_prenom($_POST['prenom']);
		$data['errors'] = $validator->get_errors();

		// Validation métier
		if ($_POST['mail'] != $user->get_mail()) {
			if (\App\Models\User::mail_uniq($_POST['mail']) != false) { 
				$data['errors']['mail']['err_user_mail_01'] = "Cette adresse mail n'est pas disponible";
			}
		}

		if (!empty($data['errors'])) {
			$data['success'] = false;
		}
		// Mise à jour de l'utilisateur en base de données
		else {
			$data['success'] = true;
			$user->set_mail($_POST['mail']);
			$user->set_nom($_POST['nom']);
			$user->set_prenom($_POST['prenom']);
			$user->update_informations();
		}
		echo json_encode($data);
	}

	/**
	 * Appelé en AJAX, permet de mettre à jour le mot de passe de l'utilisateur connecté
	 */
	public function edit_pass($user) {

		// Validation générale
		$validator = new Validator();
		$validator->check_password_content($_POST['new_password'], true);
		$validator->check_password_length($_POST['new_password'], true);
		$data['errors'] = $validator->get_errors();

		// Validation métier
		if (!User::check($user->get_mail(), sha1($_POST['password']))) {
			$data['errors']['password']['err_pass_correct'] = "Mot de passe incorrect";
		}
		if ($_POST['new_password'] != $_POST['new_password_confirm']) {
			$data['errors']['new_password_confirm']['err_pass'] = "Les deux mots de passe ne correspondent pas";
		}

		if (!empty($data['errors'])) {
			$data['success'] = false;
		}

		// Mise à jour du mot de passe en base de données
		else {
			$data['success'] = true;
			$user->set_password(sha1($_POST['new_password']));
			$data['test'] = $user->update_password();
		}
		echo json_encode($data);
	}

	/**
	 * NOT FOUND
	 */
	public function page_not_found() {
		$this->not_found();
	}
}