<?php
namespace App\Controller;

use App\Model\AppModel;

class AppController
{

    /**
     * Chemin de la vue à charger
     * @var string
     */
    protected $viewPath;
    /**
     * Chemin du layout à charger
     * @var string
     */
    protected $layout;
    /**
     * Interactions avec la base données
     * @var AppModel
     */
    protected $model;
    /**
     * Permet de stocker l'ensemble des variables à envoyer à la vue sous forme de tableau
     * @var array
     */
    protected $vars;
    /**
     * Permet de renvoyer une unique vue
     * @var bool
     */
    private $render;

    /**
     * AppController constructor.
     * @param string $url
     */
    public function __construct(string $url = NULL)
    {
        if (!is_null($url))
        {
            $this->haveSession($url);
        }
        $this->render = false;
        $this->vars = [];
        $this->viewPath = APP.DS."View".$url.'.php';
        if (is_null($this->layout))
        {
            $this->layout = APP.DS.'View'.DS.'layout'.DS.'default.php';
        }
    }

    /**
     * Permet d'afficher le layout et la vue en fonction et d'ajouter les variables à envoyer ($vars)
     */
    public function render() {
        if (!$this->render)
        {
            ob_start();
            extract($this->vars);
            require $this->viewPath;
            $content_for_layout = ob_get_clean(); //On récupère le contenu pour le layout
            require $this->layout;
            $this->render = true;
        }
    }

    /**
     * Génère une page Erreur de type 404
     */
    public function e404()
    {
        header("HTTP/1.0 404 Not Found");
        $this->viewPath = APP.DS.'View'.DS.'error'.DS.'error.php';
        $this->layout = APP.DS.'View'.DS.'layout'.DS.'modal.php';
        $this->render();
        die();
    }

    /**
     * Retourne un objet de type Model en fonction du nom de la Table en paramètres
     * @param string $name
     * @return AppModel
     */
    protected function loadModel(string $name)
    {
        $class_name = 'App\Model\\'.ucfirst($name);
        return new $class_name();
    }

    /**
     * Permet de rediriger vers une autre page suivant l'URL passé en paramètres
     * @param string $url
     */
    protected function redirect(string $url)
    {
        header('Location: ../'.$url);
        die;
    }

    /**
     * Créer une nouvelle session dans $_SESSION
     * @param array $data
     */
    protected function createSession(array $data)
    {
        $_SESSION['user'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        if (isset($data['id']))
        {
            $_SESSION['id'] = $data['id'];
        }
        if (isset($data['admin']) && $data['admin'] == 1)
        {
            $_SESSION['admin'] = "admin";
        }
    }

    /**
     * Stocke un message Flash en Session
     * @param string $message
     */
    protected function setFlash(string $message) {
        $_SESSION['flash'] = $message;
    }

    /**
     * Renvoie un message flash stocké et supprime le paramètre flash en session
     * @return string
     */
    public function getFlash(): string
    {
        $message = "";
        if (isset($_SESSION['flash']))
        {
            $message = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }
        return $message;
    }

    /**
     * Supprime une session
     */
    protected function remove_session()
    {
        session_destroy();
        session_reset();
    }

    /**
     * Crée un cookie pour stocker l'identifiant pendant 30 jours
     * @param string $email
     */
    protected function create_cookie(string $email)
    {
        if (isset($_COOKIE['remember_me']))
        {
            setcookie('email', $email, strtotime( '+30 days' ));
        }
    }

    /**
     * Supprime le cookie passée en paramètres
     * @param string $cookie
     */
    protected function remove_cookie(string $cookie)
    {
        setcookie($cookie, $_COOKIE[$cookie], 1);
    }

    /**
     * Calcul de l'ensemble des variables nécessaires à la pagination
     * @param int $number
     * @return array
     */
    protected function paginate(int $number): array
    {
        if (isset($_GET['page']))
        {
            $page = $_GET['page'];
        }
        else
        {
            $page = 1;
        }
        $perPage = 10;
        $pages = (int) (($number/$perPage)+1);
        $max = ($perPage*$page)-1;
        $min = ($perPage*($page-1));
        if ($max > $number)
        {
            $max = $number;
        }
        return compact('page', 'pages', 'min', 'max');
    }

    /**
     * Vérifie si l'utilisateur a un session ouverte quand il accède au site
     * Sinon renvoie vers la page de connexion du SIte
     *
     * @param string $url
     */
    private function haveSession(string $url)
    {
        if (($url != '/users/login') && ($url != '/users/create'))
        {
            if (!isset($_SESSION['user']))
            {
                $this->redirect('users/login');
            }
        }

    }

}
