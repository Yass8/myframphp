<?php 

class Model {
    protected $pdo;

    public function __construct() {
        try {
            $this->$pdo = new PDO('mysql:host=localhost;dbname=photoshare_bdd', 'root', 'root');
        } catch (PDOException $e){
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}