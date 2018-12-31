<?php
namespace App\Model;

/**
 * Requêtes avec la table users
 * Class Users
 * @package App\Model
 */
class Users extends AppModel
{

    /**
     * @var string
     */
    protected $table = "users";

    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Permet de retrouver un utilisateur avec son email
     * @param $email
     * @return mixed|null
     */
    public function find_one_with_mail($email)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE email=:email;';
        $req = $this->pdo->prepare($sql);
        $req->execute(array(':email' => $email));
        $data = $req->fetch();
        if (!empty($data))
        {
            return $data;
        }
        return NULL;
    }

}
