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

            $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

            // Exécuter avec les valeurs du tableau
            return $stmt->execute(array_values($data));
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Récupère toutes les lignes d'une table.
     *
     * @param string $table Le nom de la table à interroger.
     * @return array Un tableau des résultats.
     */
    public function selectAll(string $table): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les lignes d'une table correspondant aux conditions.
     *
     * @param string $table Le nom de la table.
     * @param array $conditions Les conditions sous forme de tableau associatif ['colonne' => 'valeur'].
     * @return array Un tableau des résultats correspondant aux conditions.
     */
    public function selectWhere(string $table, array $conditions): array
    {
        $sql = "SELECT * FROM {$table} WHERE ";
        $whereParts = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereParts[] = "{$column} = :{$column}";
            $params[":{$column}"] = $value;
        }

        $sql .= implode(' AND ', $whereParts);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une seule ligne d'une table en fonction d'un attribut.
     *
     * @param string $table Le nom de la table.
     * @param string $attribute La colonne sur laquelle appliquer la condition.
     * @param mixed $value La valeur de la condition.
     * @return array|false Un tableau contenant la ligne ou `false` si aucune ligne ne correspond.
     */
    public function selectOne(string $table, string $attribute, $value)
    {
        $sql = "SELECT * FROM {$table} WHERE {$attribute} = :value LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':value' => $value]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour une ligne d'une table en fonction d'une condition unique.
     *
     * @param string $table Le nom de la table.
     * @param array $data Les données à mettre à jour sous forme ['colonne' => 'valeur'].
     * @param string $column La colonne pour la condition.
     * @param mixed $conditionValue La valeur de la condition.
     * @return bool Succès ou échec de la mise à jour.
     */
    public function update(string $table, array $data, string $column, $conditionValue): bool
    {
        if ($this->selectOne($table,$column,$conditionValue)) {
            // Préparer les colonnes pour le SET
            $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));

            $stmt = $this->pdo->prepare("UPDATE $table SET $set WHERE $column = ?");

            // Exécuter avec des valeurs "SET" suivies de la condition
            return $stmt->execute([...array_values($data), $conditionValue]);
        } else {
            header("Location: " . BASE_URL . '/errors/404');
            exit;
        }
        
        
    }

    /**
     * Supprime une ligne d'une table en fonction d'une condition unique.
     *
     * @param string $table Le nom de la table.
     * @param string $column La colonne pour la condition.
     * @param mixed $conditionValue La valeur de la condition.
     * @return bool Succès ou échec de la suppression.
     */
    public function delete(string $table, string $column, $conditionValue): bool
    {
        if ($this->selectOne($table,$column,$conditionValue)) {

            $stmt = $this->pdo->prepare("DELETE FROM $table WHERE $column = ?");

            return $stmt->execute([$conditionValue]);
        } else {
            header("Location: " . BASE_URL . '/errors/404');
            exit;
        }
    }
    


}
