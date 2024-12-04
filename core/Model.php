<?php 

class Model {
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USER, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Insère des données dans une table.
     * 
     * @param string $table Nom de la table
     * @param array $data Tableau associatif (colonne => valeur)
     * @return bool True si l'insertion a réussi, false sinon
     */
    public function create(string $table, array $data): bool {
        try {
            // Préparer les noms des colonnes et les valeurs
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));

            // Requête SQL d'insertion
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);

            // Exécuter avec les valeurs du tableau
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
            return false;
        }
    }
}
