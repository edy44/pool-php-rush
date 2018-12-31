<?php
namespace App\Controller;

use App\Model\Categories;
use App\Model\Products;

/**
 * Gère l'ensemble des fonctions liées à un produit
 * Class ProductsController
 * @package App\Controller
 */
class ProductsController extends AppController
{

    /**
     * @var Categories
     */
    private $categories;
    /**
     * @var Products
     */
    protected $model;
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
     * @param $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
        $this->model = $this->loadModel("products");
        $this->categories = $this->loadModel("categories");
    }

    /**
     * Imp: Page d'accueil du Site
     * Affichage de l'ensemble des produits listés par catégories et paginés
     */
    public function index()
    {
        $categories = $this->display_categories();
        if (isset($_GET['id']))
        {
            $categories_to_display = [];
            $category = $this->categories->find_one($_GET['id']);
            $children = $this->display_categories(NULL, $category['id']);
            $categories_to_display[] = $category['id'];
            foreach ($children as $child)
            {
                $categories_to_display[] = $child['id'];
            }
            $ids = implode(', ', $categories_to_display);
            $products = $this->model->find_all_with_category($ids);
            $number = $this->model->count_all_with_category($ids)-1;
        }
        else
        {
            $products = $this->model->find_all();
            $number = $this->model->count_all()-1;
        }
        if (empty($products))
        {
            $error['data'] = "Un problème est apparu lors de l'affichage des produits";
        }
        $data = $this->paginate($number);
        extract($data);
        $this->vars = compact('page', 'pages', 'min', 'max', 'products', 'categories', 'error', 'number');
    }

    /**
     * Affichage de la vue détaillé d'un produit en fonction de son identifiant
     * @param int $id
     */
    public function view(int $id)
    {
        $id = (int)$id;
        if($id != 0)
        {
            $data = $this->model->find_one_with_id($id);
            extract($data);
            $this->vars = compact('name', 'id', 'price', 'category');
        }
        else
        {
            $this->e404();
        }
    }

    /**
     * Affiche les résultats du moteur de recherche
     * @param string $search
     * @param array $order
     */
    public function search(string $search = "")
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
        $categories = $this->display_categories();
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