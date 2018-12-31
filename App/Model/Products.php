<?php
namespace App\Model;

use PDO;

/**
 * Requêtes avec la table products
 * Class Products
 * @package App\Model
 */
class Products extends AppModel
{
    /**
     * @var string
     */
    protected $table = "products";

    /**
     * Products constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|null $conditions
     * @return array
     */
    public function find_all(array $conditions = NULL): array
    {
        $sql = 'SELECT products.name AS name, products.id AS id, products.price AS price, categories.name AS category  
          FROM ' . $this->table . ' INNER JOIN categories 
          WHERE products.category_id=categories.id;';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * @param array|NULL $conditions
     * @return int
     */
    public function count_all(array $conditions = NULL): int
    {
        $sql = 'SELECT COUNT(id) FROM '.$this->table.';';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        $data = $req->fetch();
        if (!empty($data)) {
            return (int) $data[0];
        }
        return 0;
    }

    /**
     * @param string $ids
     * @return int
     */
    public function count_all_with_category(string $ids): int
    {
        $sql = 'SELECT COUNT(products.id) FROM ' . $this->table . ' INNER JOIN categories 
        WHERE products.category_id=categories.id AND (products.category_id IN ('.$ids.'));';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        $data = $req->fetch();
        if (!empty($data)) {
            return (int) $data[0];
        }
        return 0;
    }

    /**
     * @param string $ids
     * @return array
     */
    public function find_all_with_category(string $ids): array
    {
        $sql = 'SELECT products.name AS name, products.id AS id, products.price AS price, categories.name AS category  
          FROM ' . $this->table . ' INNER JOIN categories 
          WHERE products.category_id=categories.id AND (products.category_id IN ('.$ids.'));';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function find_one_with_name(string $name)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE name=:name;';
        $req = $this->pdo->prepare($sql);
        $req->execute(array(':name' => $name));
        return $req->fetch();
    }

    /**
     * @param int $id
     * @return mixed|null
     */
    public function find_one_with_id(int $id)
    {
        $sql = 'SELECT products.name AS name, products.id AS id, products.price AS price, categories.name AS category  
        FROM ' . $this->table . ' INNER JOIN categories 
        WHERE products.category_id=categories.id AND products.id='.$id.';';
        $req = $this->pdo->query($sql);
        return $req->fetch();
    }

    /**
     * Rechercher un produit selon son nom, sa catégorie, et son prix
     * @param string $string
     * @return array
     */
    public function search_word(string $string, array $order = [])
    {
        $sql = "SELECT products.name AS name, products.id AS id, products.price AS price, categories.name AS category 
        FROM ".$this->table." INNER JOIN categories 
        WHERE products.category_id=categories.id AND ";
        if (intval($string) != 0)
        {
            $sql .= "products.price LIKE '%".$string."%'";
        }
        else
        {
            $sql .= "(products.name LIKE '%".$string."%' OR categories.name LIKE '%".$string."%')";
        }
        $sql .= " ORDER BY ".$order['column']." ".$order['direction'];
        $sql .= ";";
        $req = $this->pdo->query($sql);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

}
