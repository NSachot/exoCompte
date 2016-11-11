<?php

namespace Core;

use \PDO;

/**
 * Connexion générique à la base de données de l'application
 * Permet aussi bien de se connecter à une base de données mysql que sqlite
 * Class Database
 */
class Database {

	/**
	 * Type de la base de données : surtout sqlite ou mysql
	 * @var string db_type
	 */
	private $db_type;

	/**
	 * Nom ou chemin de la base de données (seul paramètre de connexion utilisé avec sqlite)
	 * @var string db_name
	 */
	private $db_name;

	/**
	 * @var string db_user
	 */
	private $db_user;

	/**
	 * @var string db_pass
	 */
	private $db_pass;

	/**
	 * @var string db_host
	 */
	private $db_host;

	/**
	 * @var PDO
	 */
	private $pdo;

	/**
	 * @param $db_type string
	 * @param $db_name string
	 * @param $db_user string
	 * @param $db_pass string
	 * @param $db_host string
	 */
	public function __construct($db_type, $db_name, $db_user, $db_pass, $db_host) {
		$this->db_type = $db_type;
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_host = $db_host;
	}

	/**
	 * @return PDO
	 */
	public function getPDO() {
		// test pour ne faire qu'une seule connexion à la base de données
		if ($this->pdo == null) {
			$pdo = new PDO(
				$this->db_type.':'.$this->db_name.($this->db_host ? ';host='.$this->db_host : ''), 
				($this->db_user ? $this->db_user : ''), 
				($this->db_pass ? $this->db_pass : '')
				);
	    	$this->pdo = $pdo;
		}
    	return $this->pdo;
	}

	/**
	 * @param $statement string
	 * @param $class_name string
	 * @return array
	 */
	public function query($statement, $class_name) {
		$req = $this->getPDO()->query($statement);
		return $req->fetchAll(PDO::FETCH_CLASS, $class_name);
	}

	/**
	 * @param $statement string
	 * @param $class_name string
	 * @param $attributes array
	 * @param $one boolean
	 * @return array
	 */
	public function prepare($statement, $class_name = null, $attributes, $one = false) {
		$req = $this->getPDO()->prepare($statement);
		$req->execute($attributes);
		if ($class_name != null) {
			$req->setFetchMode(PDO::FETCH_CLASS, $class_name);
		} else {
			$req->setFetchMode(PDO::FETCH_ASSOC);
		}
		if ($one) {
			return $req->fetch();
		} else {
			return $req->fetchAll();
		}
	}

	/**
	 * @param $statement string
	 * @param $attributes array
	 * @return array
	 */
	public function update($statement, $attributes) {
		$req = $this->getPDO()->prepare($statement);
		return $req->execute($attributes);
	}
}