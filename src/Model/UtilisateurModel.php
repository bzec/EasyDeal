<?php
namespace App\Model;

use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;;

class UtilisateurModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function verif_login_mdp_Utilisateur($login,$mdp){
        $sql = "SELECT id_utilisateur,nom_utilisateur,prenom_utilisateur,adresseMail_utilisateur,password_utilisateur,droit FROM Utilisateur WHERE adresseMail_utilisateur = ? AND password_utilisateur = ?";
        $res=$this->db->executeQuery($sql,[$login,md5($mdp)]);   //md5($mdp);
        if($res->rowCount()==1)
            return $res->fetch();
        else
            return false;
    }
    // public function verif_login_mdp_Utilisateur($login,$mdp){
    // 	$sql = "SELECT id,login,password,droit FROM users WHERE login = ? AND password = ?";
    // 	$res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
    // 	if($res->rowCount()==1)
    // 		return $res->fetch();
    // 	else
    // 		return false;
    // }

}