<?php
namespace App\Controller\Admin;

use App\Model\Categories;
use App\Model\Products;

/**
 * Gère l'ensemble des fonctions liées à un produit pour la partie Admin
 * Class UsersController
 * @package App\Controller\Admin
 */
class ProductsController extends AppAdminController
{

    /**
     * @var Products
     */
    protected $model;
    /**
     * @var Categories
     */
    private $categories;
    /**
     * @var int
     */
    private static $number = 0;
    /**
     * @var int
     */
    private static $level = 0;

    /**
     * ProductsController constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url);
        $this->model = $this->loadModel("products");
        $this->categories = $this->loadModel("categories");
    }

    /**
     * Permet à un administrateur d'enregistrer un nouveau produit sur le site
     */
    public function create()
    {
        $category_id = 0;
        if (!empty($_POST)) {
            extract($_POST);
            $error = [];
            if (empty($name))
            {
                $error['name'] = "Nom incorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_name($name);
                if ($exist)
                {
                    $error['name'] = "Le produit ".$name." existe déjà dans la base de données";
                }
            }
            if (empty($price) || !is_float((float)$price) || $price <= 0)
            {
                $error['price'] = "Prix incorrect";
            }
            if (!is_int((int)$category_id))
            {
                $error['category_id'] = "Categorir incorrecte";
            }
            if (empty($error))
            {
                $data = compact('name', 'price', 'category_id');
                if ($this->model->create($data))
                {
                    $success = "Le produit ".$name." a bien été créé";
                    $this->setFlash($success);
                    $this->redirect("products/index");
                }
                else
                {
                    $error['data'] = "Un problème est survenu lors de la création du produit ".$name;
                }
            }
        }
        $name = "";
        $category_id = 0;
        $price = "";
        $categories = $this->display_categories();
        $this->vars = compact('name', 'price', 'category_id', 'categories', 'error');
    }

    /**
     * Affiche l'ensemble des produits enregistrés sur le site
     */
    public function index()
    {
        $products = $this->model->find_all();
        $number = $this->model->count_all()-1;
        if (empty($products))
        {
            $error['data'] = "Un problème est apparu lors de l'affichage des produits";
        }
        $data = $this->paginate($number);
        extract($data);
        $this->vars = compact('page', 'pages', 'min', 'max', 'products', 'categories', 'error', 'number');
    }

    /**
     * Supprime un produit en fonction de son identifiant
     * @param int $id
     */
    public function delete(int $id)
    {
        $product = $this->model->find_one($id);
        $this->model->delete($id);
        $success = "La catégorie ".$product['name']." a bien été supprimé";
        $this->setFlash($success);
        $this->redirect("products/index");
    }

    /**
     * Permet de modifier les paramètres d'un produit selon son identifiant
     * @param int $id
     */
    public function edit(int $id)
    {
        $category_id = 0;
        if (!empty($_POST)) {
            extract($_POST);
            $error = [];
            if (empty($name))
            {
                $error['name'] = "Nom incorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_name($name);
                if (($exist) && ($id != $exist['id']))
                {
                    $error['name'] = "Le produit ".$name." existe déjà dans la base de données";
                }
            }
            if (empty($price) || !is_float((float)$price) || $price <= 0)
            {
                $error['price'] = "Prix incorrect";
            }
            if (!is_int((int)$category_id))
            {
                $error['category_id'] = "Catégorie incorrecte";
            }
            if (empty($error))
            {
                $data = compact('id', 'name', 'price', 'category_id');
                if ($this->model->edit($data))
                {
                    $success = "Le produit ".$name." a bien été modifié";
                    $this->setFlash($success);
                    $this->redirect("products/index");
                }
                else
                {
                    $error['data'] = "Un problème est survenu lors de la modification du produit".$name;
                }
            }
        }
        else
        {
            $data = $this->model->find_one($id);
            //$categories = $this->categories->find_all();
            $categories = $this->display_categories($id);
            extract($data);
        }
        $this->vars = compact( 'id', 'name', 'price', 'category_id', 'categories', 'error');
    }

    /**
     * Affiche les résultats du moteur de recherche
     * @param $search
     */
    public function search($search = "")
    {
        $order = [];
        if (isset($_GET['sort']))
        {
            if ($_GET['sort'] == "name_asc")
            {
                $order['column'] = 'name';
                $order['direction'] = 'ASC';
            }
            if ($_GET['sort'] == "name_dsc")
            {
                $order['column'] = 'name';
                $order['direction'] = 'DESC';
            }
            if ($_GET['sort'] == "price_asc")
            {
                $order['column'] = 'price';
                $order['direction'] = 'ASC';
            }
            if ($_GET['sort'] == "price_dsc")
            {
                $order['column'] = 'price';
                $order['direction'] = 'DESC';
            }
        }
        else
        {
            $order['column'] = 'name';
            $order['direction'] = 'ASC';
        }
        if (isset($_GET['search']))
        {
            $search = $_GET['search'];
        }
        if(!empty($search))
        {
            $products = $this->model->search_word($search, $order);
            $number = count($products)-1;
        }
        else
        {
            $error['search'] = 'Vous devez remplir le champ avant de lancer la recherche';
            $products = [];
            $number = 0;
        }
        if (empty($products))
        {
            $success = "Aucun résultat pour votre recherche : ".$search;
            $this->setFlash($success);
            $this->redirect("products/index");
        }
        $data = $this->paginate($number);
        extract($data);
        $this->vars = compact('page', 'pages', 'min', 'max', 'products', 'categories', 'error', 'number', 'search');

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
        $children = $this->categories->find_children($id, $parent_id);
        foreach ($children as $child)
        {
            $categories[self::$number]['name'] = $child['name'];
            $categories[self::$number]['id'] = $child['id'];
            $categories[self::$number]['level'] = $level;
            self::$number++;
            $data = $this->categories->find_children($id, $child['id']);
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
