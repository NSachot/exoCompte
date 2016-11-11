<?php
namespace Core;

/**
 * Class Validator
 */
class Validator {

	/**
	 * @var array Liste des erreurs 
	 */
	private $errors = array();

	/**
	 * @var string Liste des accents autorisés
	 */
	private $accents = 'ÀÁÂÃÄÅÇÑñÇçÈÉÊËÌÍÎÏÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöøùúûüýÿ';

	/**
	 * @return array $errors Liste des erreurs
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * @param $mail string
	 */
	public function check_mail($mail) {
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) { 
			$this->errors['mail']['err_mail_01'] = "Adresse mail incorrecte";
		}
	}

	/**
	 * Caractères autorisés : lettres minuscules/majuscules, accents, tirets, apostrophes, espaces
	 * @param $nom string
	 */
	public function check_nom_de_famille($nom) {
		if (!preg_match('/^[a-zA-Z'.$this->accents.'\-\' ]+$/', $nom)) { 
			$this->errors['nom']['err_nom_01'] = "Nom invalide (lettres, accents, tirets, apostrophes, espaces autorisés)";
		}
	}

	/**
	 * Caractères autorisés : lettres minuscules/majuscules, accents, tirets
	 * @param $prenom string
	 */
	public function check_prenom($prenom) {
		if (!preg_match('/^[a-zA-Z'.$this->accents.'\-]+$/', $prenom)) { 
			$this->errors['prenom']['err_prenom_01'] = "Prenom invalide (lettres, accents, tirets autorisés)";
		}
	}

	/**
	 * Caractères autorisés : caractères alphanumériques
	 * @param $prenom string
	 */
	public function check_password_content($password, $new = false) {
		if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) { 
			$this->errors[($new) ? 'new_password' : 'password']['err_pass_01'] = "Le mot de passe ne peut contenir que des chiffres et des lettres OOO";
		}
	}

	/**
	 * Caractères autorisés : caractères alphanumériques
	 * @param $prenom string
	 */
	public function check_password_length($password, $new = false) {
		if (strlen($password)<6 || strlen($password)>12) { 
			$this->errors[($new) ? 'new_password' : 'password']['err_pass_02'] = "Le mot de passe doit contenir de 6 à 12 caractères";
		}
	}
	
}