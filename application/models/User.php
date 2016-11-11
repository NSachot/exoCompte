<?php
namespace App\Models;

/**
 * Class User
 * Permet de gérer un utilisateur de l'application
 */
class User {

    /**
     * @var int Identifiant autoincrémenté par la BD
     */
    private $id;

    /**
     * @var string Adresse mail
     */
    private $mail;

    /**
     * @var string Nom
     */
    private $nom;

    /**
     * @var string Prenom
     */
    private $prenom;

    /**
     * @var string Password
     */
    private $password;

    /**
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @param $id string
     */
    public function set_id($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function get_mail() {
    	return $this->mail;
    }

    /**
     * @param $mail string
     */
    public function set_mail($mail) {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function get_nom() {
        return $this->nom;
    }

    /**
     * @param $nom string
     */
    public function set_nom($nom) {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function get_prenom() {
        return $this->prenom;
    }

    /**
     * @param $prenom string
     */
    public function set_prenom($prenom) {
        $this->prenom = $prenom;
    }

    /**
     * @param $password string
     */
    public function set_password($password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function get_password() {
        return $this->password;
    }

    /**
     * @param $user User
     * @return User
     */
    public function insert() {
        $app = \App::get_instance();
        $app->get_db()->prepare(
            'INSERT INTO user (mail, nom, prenom, password, date_inscription, date_derniere_connexion) 
            VALUES(:mail, :nom, :prenom, :password, :date_inscription, :date_derniere_connexion)', 
            __CLASS__,
            array(
                'mail' => $this->mail,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'password' => $this->password,
                'date_inscription' => date("Y-m-d H:i:s"),
                'date_derniere_connexion' => date("Y-m-d H:i:s")
                )
        );
        $result = $app->get_db()->prepare(
            'SELECT id FROM user WHERE mail = :mail', 
            null,
            array(':mail' => $this->mail),
            true
        );
        return $result['id'];
    }

    /**
     * @param $new_password string
     */
    public function update_password() {
        $app = \App::get_instance();
        return $app->get_db()->update(
            'UPDATE user SET password = :password WHERE id = :id', 
            array( 
                ':id' => $this->get_id(),
                ':password' => $this->get_password()
                )
        );
    }

    /**
     * @param $data array Nouvelles informations personnelles de l'utilisateur
     */
    public function update_informations() {
        $app = \App::get_instance();
        return $app->get_db()->update(
            'UPDATE user SET mail = :mail, nom = :nom, prenom = :prenom WHERE id = :id', 
            array( 
                ':id' => $this->get_id(),
                ':mail' => $this->get_mail(),
                ':nom' => $this->get_nom(),
                ':prenom' => $this->get_prenom()
                )
        );
    }

    /**
     * Trouve un User à partir de son adresse mail
     * @param $mail String
     * @return User ou false
     */
    public static function mail_uniq($mail) {
        $app = \App::get_instance();
        return $app->get_db()->prepare(
            'SELECT * FROM user WHERE mail = :mail', 
            __CLASS__,
            array(':mail' => $mail),
            true
        );
    }

    /**
     * Trouve un User à partir de son adresse id
     * @param $id int
     * @return User
     */
    public static function find_by_id($id) {
        $app = \App::get_instance();
        return $app->get_db()->prepare(
            'SELECT * FROM user WHERE id = :id', 
            __CLASS__,
            array(':id' => $id),
            true
        );
    }

    /**
     * Connexion d'un utilisateur
     * @param $mail String
     * @param $password String Mot de passe en sha1
     * @return User ou false
     */
    public static function check($mail, $password) {
        $app = \App::get_instance();
        $db = $app->get_db();
        // Recherche de l'utilisateur
        $user = $db->prepare(
            'SELECT id, mail, nom, prenom FROM user WHERE mail = :mail AND password = :password', 
            __CLASS__,
            array(
                ':mail' => $mail, 
                ':password' => $password
                ),
            true
        );
        // Si l'utilisateur est connecté, mise à jour de la dernière date de connexion
        if ($user != null) {
            $db->update(
                'UPDATE user SET date_derniere_connexion = :date_derniere_connexion WHERE id = :id', 
                array(
                    ':date_derniere_connexion' => date("Y-m-d H:i:s"), 
                    ':id' => $user->get_id()
                    )
            );
        }
        return $user;
    }

    /**
     * @return User
     */
    public static function user_connected() {
        if (isset($_SESSION['id'])) {
            return self::find_by_id($_SESSION['id']);
        } else {
            return false;
        }
    }
}