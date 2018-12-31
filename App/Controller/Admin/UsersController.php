<?php
namespace App\Controller\Admin;

use App\Model\Users;

/**
 * Gère l'ensemble des fonctions liées à un utilisateur pour la partie Admin
 * Class UsersController
 * @package App\Controller\Admin
 */
class UsersController extends AppAdminController
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
     * Affiche l'ensemble des utilisateurs enregistrés sur le site
     */
    public function index()
    {
        $data = $this->model->find_all();
        $number = $this->model->count_all();
        $vars = $this->paginate($number);
        extract($vars);
        $this->vars = compact('data', 'min', 'max', 'number', 'page', 'pages');
    }

    /**
     * Permet à un administrateur d'enregistrer un nouvel utilisateur sur le site
     */
    public function create()
    {
        $admin = 0;
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
            if (empty($password_confirmation) ||
                (strlen($password_confirmation) < 3) ||
                (strlen($password_confirmation) > 10) ||
                ($password != $password_confirmation))
            {
                $error['password'] = "Mot de Passe ou Confirmation du Mot de Passe Invalides";
            }
            if (!is_int((int)$admin))
            {
                $error['admin'] = "Droits d'utilisateur Invalides";
            }
            if (empty($error))
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $vars = compact('username', 'email', 'password', 'admin');
                $this->model->create($vars);
                $success = "L'utilisateur ".$username." a bien été ajouté";
                $this->redirect("users/index");
            }
        }
        $username = "";
        $email = "";
        $password = "";
        $admin = "";
        $password_confirmation = "";
        $this->vars = compact('success', 'username', 'email', 'password', 'password_confirmation', 'admin', 'error');
    }

    /**
     * Permet à un administrateur de modifier les informations d'utilisateur sur le site
     * @param int $id
     */
    public function edit(int $id)
    {
        if (!empty($_POST)) {
            extract($_POST);
            $error = [];
            if (empty($username) ||
                (strlen($username) < 3) ||
                (strlen($username) > 10))
            {
                $error['username'] = "Nom incorrect";
            }
            if (empty($email) ||
                (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email))) {
                $error['email'] = "Email invorrect";
            }
            else
            {
                $exist = $this->model->find_one_with_mail($email);
                if (($exist) && ($id != $exist['id']))
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
                    $success = "Les informations de l'utilisateur ".$username." ont bien été modifiées";
                    $this->setFlash($success);
                    $this->redirect("users/index");
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
                $admin = (int)$data['admin'];
                $password = "";
                $password_confirmation = "";
            }
        }
        $this->vars = compact( 'id', 'username', 'email', 'password', 'password_confirmation', 'admin', 'error');
    }

    /**
     * Permet à un administrateur de supprimer un utilisateur sur le site
     * @param int $id
     */
    public function delete(int $id)
    {
        $user = $this->model->find_one($id);
        $this->model->delete($id);
        $success = "La catégorie ".$user['username']." a bien été supprimé";
        $this->setFlash($success);
        $this->redirect('users/index');
    }

}
