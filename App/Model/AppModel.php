<?php
namespace App\Model;

use PDO;
use PDOException;

/**
 * Class AppModel
 * Gère la connexion à la base de données
 * Et contient les requêtes génériques à toutes les tables
 * @package App\Model
 */
class AppModel
{

    /**
     * Paramètres de connexion à la base de données pour l'objet PDO
     */
    const DB_HOST = 'localhost';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = 'root';
    const DB_PORT = 80;
    const DB_NAME = 'pool_php_rush';

    /**
     * @var PDO
     */
    protected $pdo;
    /**
     * @var string
     */
    protected $table;

    /**
     * AppModel constructor.
     */
    public function __construct()
    {
        if (is_null($this->pdo))
        {
            $this->pdo = $this->connect_db(self::DB_HOST,
                self::DB_USERNAME,
                self::DB_PASSWORD,
                self::DB_PORT,
                self::DB_NAME);
        }
    }

    /**
     * Permet de créer un enregistrement dans la table
     * Le tableau data contient en clé le nom des paramètres et en valeur leur valeur
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $vars = [];
        $sql = 'INSERT INTO '.$this->table.' SET ';
        foreach ($data as $key => $value)
        {
            $sql .= $key.'=:'.$key.', ';
            $vars[':'.$key] = $value;
        }
        $sql = trim($sql, ', ');
        $sql .= ';';
        $req = $this->pdo->prepare($sql);
        return $req->execute($vars);
    }

    /**
     * Permet de modifier un enregistrement dans la table
     * Le tableau data contient en clé le nom des paramètres et en valeur leur valeur
     * @param array $data
     * @return bool
     */
    public function edit(array $data): bool
    {
        $vars = [];
        $sql = 'UPDATE '.$this->table.' SET ';
        foreach ($data as $key => $value)
        {
            if (($key != "id"))
            {
                $sql .= $key.'=:'.$key.', ';
            }
            $vars[':'.$key] = $value;
        }
        $sql = trim($sql, ', ');
        $sql .= ' WHERE id=:id;';
        $req = $this->pdo->prepare($sql);
        return $req->execute($vars);
    }

    /**
     * Permet de sélectionner un unique enregistrement suivant son identifiant
     * @param $id
     * @return array
     */
    public function find_one($id)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE id=:id;';
        $req = $this->pdo->prepare($sql);
        $req->execute(array(':id' => $id));
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de renvoyer l'ensemble des éléments selon les conditions passées en paramètre
     * Renvoie NULL si rien n'a été trouvé
     * Le tableau conditions contient en clé le nom du paramètre et en valeur la valeur à rechercher
     * @param array|null $conditions
     * @return array
     */
    public function find_all(array $conditions = NULL): array
    {
        $vars = [];
        $sql = 'SELECT * FROM '.$this->table;
        if (!is_null($conditions))
        {
            $sql .= ' WHERE ';
            foreach ($conditions as $key => $value) {
                if (is_null($value))
                {
                    $sql .= $key.' IS NULL, ';
                }
                else
                {
                    $sql .= $key.'=:'.$key.', ';
                    $vars[':'.$key] = $value;
                }
            }
            $sql = trim($sql, ', ');
        }
        $sql .= ';';
        $req = $this->pdo->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte l'ensemble des éléments suivant des conditions rentrés en paramètre
     * @param array|NULL $conditions
     * @return int
     */
    public function count_all(array $conditions = NULL): int
    {
        $vars = [];
        $sql = 'SELECT count(id) FROM '.$this->table;
        if (!is_null($conditions))
        {
            $sql .= ' WHERE ';
            foreach ($conditions as $key => $value) {
                if (is_null($value))
                {
                    $sql .= $key.' IS NULL, ';
                }
                else
                {
                    $sql .= $key.'=:'.$key.', ';
                    $vars[':'.$key] = $value;
                }
            }
            $sql = trim($sql, ', ').';';
        }
        $req = $this->pdo->prepare($sql);
        $req->execute($vars);
        $data = $req->fetch();
        if (!empty($data))
        {
            return $data[0];
        }
        return 0;
    }

    /**
     * Permet de supprimer un enregistrement dans la table suivant son identifiant
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE id=:id;';
        $req = $this->pdo->prepare($sql);
        return $req->execute(array(':id' => $id));
    }

    /**
     * Permet de se connecter à la base de données
     * Renvoie un objet de type PDO
     * @param $host
     * @param $username
     * @param $password
     * @param $port
     * @param $db
     * @return PDO
     */
    private function connect_db(string $host, string $username, string $password, int $port, string $db): PDO
    {
        try
        {
            $pdo = new PDO('mysql:dbname='.$db.';host=' .$host.';port='.$port, $username, $password);
            return $pdo;
        }
        catch (PDOException $pdoException)
        {
            $message = "Erreur de connection à la base de données\n";
            echo $message;
            die;
        }
    }

}