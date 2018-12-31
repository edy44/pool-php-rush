<?php
namespace App;

use App\Controller\AppController;

/**
 * Class Dispatcher : Permet de créer le controller et de lui appliquer la bonne méthode en fonction de l'URL
 * @package App
 */
class Dispatcher
{
    /**
     * @var bool
     */
    private $admin;
    /**
     * @var string
     */
    private $action;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $url;
    /**
     * @var bool
     */
    private $error;
    /**
     * @var array
     */
    private $params;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->error = false;
        if (!is_null($_SERVER["PATH_INFO"]))
        {
            $this->url = $_SERVER["PATH_INFO"];
        }
        else
        {
            $this->error();
        }
        if (!$this->existUrl()) {
            $this->parse($this->url);
            session_start();
            /** @var AppController $controller */
            $controller = $this->loadController();
            if (!in_array(
                $this->action,
                array_diff(
                    get_class_methods($controller),
                    get_class_methods(new AppController($this->url))
                )
            ))
            {
                $this->error();
            }
            if (!empty($_GET))
            {
                $this->params = $_GET;
            }
            else
            {
                $this->params = [];
            }
            call_user_func_array(array($controller,$this->action), $this->params);
            $controller->render();
        }
    }

    /**
     * Afficher une page Erreur 404
     */
    private function error()
    {
        (new AppController(NULL))->e404();
    }

    /**
     * Retourne un objet controller en fonction du nom de la table
     * @return AppController
     */
    private function loadController() {
        if (!$this->admin) {
            $class_name = 'App\Controller\\';
        } else {
            $class_name = 'App\Controller\Admin\\';
        }
        $class_name .= ucfirst($this->name).'Controller';
        $class_name_dir = str_replace('\\', DS, $class_name);
        $file = ROOT.DS.$class_name_dir.'.php';
        if (!file_exists($file)) {
            $this->error();
        }
        return new $class_name($this->url);
    }

    /**
     * Parse l'URL en isolant l'admin s'il existe, le nom de la table et la méthode à appeler dans le controller
     * @param $url
     */
    private function parse($url)
    {
        $url = trim($url, '/');
        $array = explode('/', $url);
        if (!empty($array))
        {
            if (count($array)==3)
            {
                if ($array[0] == "admin")
                {
                    $this->name = $array[1];
                    $this->action = $array[2];
                    $this->admin = true;
                }
                else
                {
                    $this->error = true;
                }

            }
            elseif (count($array)==2)
            {
                $this->name = $array[0];
                $this->action = $array[1];
                $this->admin = false;
            }
            else
            {
                $this->error = true;
            }
        }
        else
        {
            $this->error = true;
        }
        if ($this->error)
        {
            $this->error();
        }
    }

    /**
     * Vérifie si l'URL est un fichier qui existe
     * @return bool
     */
    private function existUrl() {
        if ($this->url === '/') {
            return false;
        }
        if (file_exists(WEBROOT.$this->url) || file_exists(ROOT.$this->url)) {
            return true;
        } else {
            return false;
        }
    }

}