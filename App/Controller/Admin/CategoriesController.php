<?php

namespace App\Controller\Admin;

use App\Model\Categories;
use App\Model\Products;

/**
 * Gère l'ensemble des fonctions liées à une catégorie pour la partie Admin
 * Class UsersController
 * @package App\Controller\Admin
 */
class CategoriesController extends AppAdminController
{

    /**
     * @var Categories
     */
    protected $model;
    /**
     * @var Products
     */
    private $products;
    /**
     * @var int
     */
    private static $number = 0;
    /**
     * @var int
     */
    private static $level = 0;

    /**
     * CategoriesController constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url);
        $this->model = $this->loadModel("categories");
        $this->products = $this->loadModel("products");
    }

    /**
     * Permet à un administrateur d'enregistrer une nouvelle catégorie sur le site
     */
    public function create()
    {
        if(!empty($_POST)) 
        {
            $parent_id = -1;
            extract($_POST);
            $error = [];
            if(empty($name) || (strlen($name) < 3))
            {
                $error['name'] = "Nom incorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_name($name);
                if ($exist)
                {
                    $error['name'] = "La catégorie ".$name." existe déjà dans la base de données";
                }
            }
            if(empty($error))
            {
                if ($parent_id != -1)
                {
                    $data = compact('name', 'parent_id');
                }
                else
                {
                    $data = compact('name');

                }
                $this->model->create($data);
                $success = "La nouvelle catégorie".$name." a bien été ajoutée";
                $this->setFlash($success);
                $this->redirect("categories/index");
            }
        }
        $name = "";
        $parent_id = 0;
        $parents = $this->display_categories();
        $this->vars = compact( 'name', 'parent_id', 'parents', 'error');
    }

    /**
     * Affiche l'ensemble des catégories parentes enregistrées sur le site
     */
    public function index()
    {
        $categories = [];
        if (isset($_GET['parent_id']))
        {
            $results = $this->model->find_all(array('parent_id' => $_GET['parent_id']));
            $parent = $this->model->find_one($_GET['parent_id']);
            $number = $this->model->count_all(array('parent_id' => $_GET['parent_id']));
        }
        else
        {
            $results = $this->model->find_all(array('parent_id' => NULL));
            $parent = array();
            $number = $this->model->count_all(array('parent_id' => NULL));
        }
        if (empty($parent))
        {
            $parent['name'] = "Aucun";
        }
        if (empty($parent['parent_id']))
        {
            $parent['parent_id'] = "";
        }
        else
        {
            $parent['parent_id'] = "?parent_id=".$parent['parent_id'];
        }
        foreach ($results as $category)
        {
            $category['count'] = $this->model->count_all(array('parent_id' => $category['id']));
            $categories[] = $category;
        }
        $data = $this->paginate($number);
        extract($data);
        if (isset($_GET['parent_id']))
        {
            $url = "?parent_id=".$_GET['parent_id']."&page=";
        }
        else
        {
            $url = "?page=";
        }
        $this->vars = compact('categories', 'parent', 'min', 'max', 'page', 'pages', 'number', 'url');
    }

    /**
     * Supprime une catégorie en fonction de son identifiant
     * @param int $id
     */
    public function delete(int $id)
    {
        $category = $this->model->find_one($id);
        $children = $this->model->find_all(array('parent_id' => $id));
        foreach ($children as $child) //Mise à jour des catégorie enfants avant la catégorie parente
        {
            $child['parent_id'] = $category['parent_id'];
            $this->model->edit($child);
        }
        var_dump($this->products);
        $products = $this->products->find_all(array('category_id' => $id));
        foreach ($products as $product)
        {
            $product['category_id'] = $category['parent_id']; //Mise à jour des produits avec la catégorie parente
            $this->products->edit($product);
        }
        $this->model->delete($id);
        $success = "La catégorie ".$category['name']." a bien été supprimée";
        $this->setFlash($success);
        $this->redirect("categories/index");
    }

    /**
     * Permet de modifier les paramètres d'une catégorie selon son identifiant
     * @param int $id
     */
    public function edit(int $id)
    {
        if (!empty($_POST)) {
            $parent_id = -1;
            extract($_POST);
            $error = [];
            if (empty($name))
            {
                $error['name'] = "Nom incorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_name($name);
                if (!is_null($exist) && ($id != $exist['id']))
                {
                    $error['name'] = "La catégorie existe déjà dans la base de données";
                }
            }
            if (empty($error))
            {
                if ($parent_id == -1)
                {
                    $parent_id = NULL;
                }
                $data = compact('id', 'name', 'parent_id');
                var_dump($data);
                if ($this->model->edit($data))
                {
                    $success = "La catégorie ".$name." a bien été modifiée";
                    $this->setFlash($success);
                    $this->redirect("categories/index");
                }
                else
                {
                    $error['data'] = "Un problème est survenu lors de la modification de la catégorie ".$name;
                }
            }
        }
        $data = $this->model->find_one($id);
        extract($data);
        if (!is_null($parent_id))
        {
            $parent = $this->model->find_one($parent_id);
            $parent = $parent['parent_id'];
        }
        else
        {
            $parent = NULL;
        }
        $parents = $this->display_categories($id);
        $this->vars = compact( 'id', 'name', 'parent_id', 'parents', 'error');
    }

    /**
     * @param int|NULL $id
     * @param int|NULL $parent_id
     * @return array
     */
    private function display_categories(int $id = NULL, int $parent_id = NULL): array
    {
        $level = self::$level;
        $categories = [];
        $children = $this->model->find_children($id, $parent_id);
        foreach ($children as $child)
        {
            $categories[self::$number]['name'] = $child['name'];
            $categories[self::$number]['id'] = $child['id'];
            $categories[self::$number]['level'] = $level;
            self::$number++;
            $data = $this->model->find_children($id, $child['id']);
            if (!empty($data))
            {
                foreach ($data as $value)
                {
                    $categories[self::$number]['name'] = $value['name'];
                    $categories[self::$number]['id'] = $value['id'];
                    $categories[self::$number]['level'] = $level;
                    self::$number++;
                }
            }
        }
        self::$level++;
        return $categories;
    }

}
