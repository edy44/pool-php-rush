<?php
namespace App\Model;

use PDO;

/**
 * Requêtes avec la table categories
 * Class Categories
 * @package App\Model
 */
class Categories extends AppModel
{

    /**
     * @var string
     */
    protected $table = "categories";

    /**
     * Categories constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Renvoie les informations d'une catégorie suivant le nom passé en paramètre
     * @param $name
     * @return array|null
     */
    public function find_one_with_name(string $name)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE name=:name;';
        $req = $this->pdo->prepare($sql);
        $req->execute(array(':name' => $name));
        return $req->fetch();
    }

    /**
     * Retourne sous forme de tableau toutes les catégories du champ parent
     * @param int|null $id
     * @param int|null $parent_id
     * @return array|null
     */
    public function find_parents(int $id = NULL, int $parent_id = NULL)
    {
        $vars = [];
        $sql = 'SELECT * FROM '.$this->table.' WHERE parent_id ';
        if (is_null($parent_id))
        {
            $sql .= 'IS NULL';
        }
        else
        {
            $sql .= $parent_id;
        }
        if (!is_null($id))
        {
            $sql .= ' AND id!=:id';
            $vars[':id'] = $id;
        }
        $sql .= ' ORDER BY name ASC;';
        $req = $this->pdo->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int|NULL $id
     * @return array
     */
    public function find_children(int $id = NULL, int $children_id = NULL)
    {
        $vars = [];
        $sql = 'SELECT * FROM '.$this->table.' WHERE parent_id ';
        if (is_null($children_id))
        {
            $sql .= 'IS NULL';
        }
        else
        {
            $sql .= '='.$children_id;
        }
        if (!is_null($id))
        {
            $sql .= ' AND id!=:id';
            $vars[':id'] = $id;
        }
        $sql .= ' ORDER BY name ASC;';
        $req = $this->pdo->prepare($sql);
        $req->execute($vars);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

}
