<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Controller de la partie Administration
 * Class AppAdminController
 * @package App\Controller\Admin
 */
class AppAdminController extends AppController
{

    /**
     * Vérifie si l'utilisateur a les droits d'accès et redéfinit le layout pour la partie Administration
     * AppAdminController constructor.
     * @param $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
        $this->is_admin();
        $this->layout = APP.DS.'View'.DS.'layout'.DS.'admin.php';
    }

    /**
     * Vérifie que l'utilisateur en session a les droits d'accès à l'administration
     * Sinon redirige vers la page d'accueil du Site
     */
    private function is_admin()
    {
        if (!isset($_SESSION['admin']))
        {
            $this->redirect('products/index');
        }
    }

}