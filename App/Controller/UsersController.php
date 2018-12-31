<?php
namespace App\Controller;

use App\Model\Users;

/**
 * Gère l'ensemble des fonctions liées à un utilisateur
 * Class UsersController
 * @package App\Controller
 */
class UsersController extends AppController
{

    /**
     * @var Users
     */
    protected $model;

    /**
     * UsersController constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url);
        $this->model = $this->loadModel("users");
    }

    /**
     * Permet de seconnecter sur le site avec son identifiant et son mot de passe
     */
    public function login()
    {
        $this->have_cookie();
        $this->is_logged();
        if (!empty($_POST)) {
            extract($_POST);
            $error = [];
            if (empty($email) ||
                (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))) {
                $error['login'] = "Login incorrect";
            }
            if (empty($password))
            {
                $error['password'] = "Mot de Passe incorrect";
            }
            if(empty($error))
            {
                $data = $this->model->find_one_with_mail($email);
                if (is_null($data)) {
                    $error['data'] = 'Email ou Mot de Passe incorrect';
                }
                $password_bdd = $data['password'];
                if (password_verify($password, $password_bdd))
                {
                    $this->createSession($data);
                    $this->create_cookie($email);
                    if (isset($_SESSION['admin']))
                    {
                        $this->redirect('admin/users/index');
                    }
                    else
                    {
                        $this->redirect('products/index');
                    }
                }
            }
        }
        $this->vars = compact('name','password', 'error');
        $this->layout =  APP.DS.'View'.DS.'layout/modal.php';
    }

    /**
     * Supprime la session et les cookies, et renvoie vers la page de connexion du site
     */
    public function logout()
    {
        $this->remove_session();
        $this->remove_cookie('email');
        $this->redirect('users/login');
        die;
    }

    /**
     * Permet de s'enregistrer sur le site
     */
    public function create()
    {
        $this->have_cookie();
        $this->is_logged();
        if (!empty($_POST)) {
            extract($_POST);
            if (empty($username) ||
                (strlen($username) < 3) ||
                (strlen($username) > 10))
            {
                $error['username'] = "Nom incorrect";
            }
            if (empty($email) ||
                (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))) {
                $error['email'] = "Email incorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_mail($email);
                if ($exist)
                {
                    $error['email'] = "Email existe déjà dans la base de données";
                }
            }
            if (empty($password) ||
                (strlen($password) < 3) ||
                (strlen($password) > 10))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            if (empty($password_confirmation) ||
                (strlen($password_confirmation) < 3) ||
                (strlen($password_confirmation) > 10) ||
                ($password != $password_confirmation))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $admin = 0;
                $vars = compact('username', 'email', 'password', 'admin');
                $this->model->create($vars);
                $success = "Vous êtes bien inscrit sur le site";
                $this->setFlash($success);
                $this->redirect('users/login');
            }
        }
        $username = "";
        $email = "";
        $password = "";
        $password_confirmation = "";
        $this->vars = compact( 'username', 'email', 'password', 'password_confirmation', 'error');
        $this->layout =  APP.DS.'View'.DS.'layout/modal.php';
    }

    /**
     * Permet de modifier le profil de l'utilisateur sur le site
     */
    public function edit()
    {
        $id = $_SESSION['id'];
        if (!empty($_POST)) {
            extract($_POST);
            $error = [];
            if (empty($username) ||
                (strlen($username) < 3) ||
                (strlen($username) > 10))
            {
                $error['username'] = "Nom invalide";
            }
            if (empty($email) ||
                (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))) {
                $error['email'] = "Email invalide";
            }
            else
            {
                $exist = $this->model->find_one_with_mail($email);
                if (!is_null($exist) && ($id != $exist['id']))
                {
                    $error['email'] = "Email existe déjà dans la base de données";
                }
            }
            if (!empty($password) && ((strlen($password) < 3) ||
                    (strlen($password) > 10)))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            if (empty($password_confirmation) && !empty($password))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            if ((!empty($password_confirmation) && (!empty($password))) && ((strlen($password_confirmation) < 3) ||
                    (strlen($password_confirmation) > 10) ||
                    ($password != $password_confirmation)))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            if (empty($error))
            {
                if (!empty($password))
                {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $data = compact('id', 'username', 'email', 'password', 'admin');
                }
                else
                {
                    $data = compact('id', 'username', 'email', 'admin');
                }
                if ($this->model->edit($data))
                {
                    $data = [
                        'username' => $username,
                        'email' => $email
                    ];
                    $this->createSession($data);
                    $this->create_cookie($email);
                    if (!empty($password))
                    {
                        $password = "";
                        $password_confirmation = "";
                    }
                    $success = "Vos informations ont bien été modifiées";
                    $this->setFlash($success);
                }
            }
        }
        else
        {
            $data = $this->model->find_one($id);
            if (!is_null($data))
            {
                $username = $data['username'];
                $email = $data['email'];
                $password = "";
                $password_confirmation = "";
            }
        }

        $this->vars = compact('id', 'username', 'email', 'password', 'password_confirmation', 'error');
    }

    /**
     * Renvoie vers les produits pour la recherche
     */
    public function search($search = "")
    {
        if(!empty($search))
        {
            $this->redirect('products/search?search='.$search);
        }
        else
        {
            $this->redirect('users/edit');
        }
    }
    
    /**
     * Si l'utilisateur a déjà son identifiant en cookie, on lui ouvre une session
     */
    private function have_cookie()
    {
        if (isset($_COOKIE['email']))
        {
            $data = $this->model->find_one_with_mail($_COOKIE['email']);
            $this->createSession($data);
        }
    }

    /**
     * Si un utilisateur a une session ouverte, il est redirigé automatiquement vers la page d'accueil du site
     */
    private function is_logged()
    {
        if (isset($_SESSION['user']))
        {
            $this->redirect('products/index');
        }
    }

}