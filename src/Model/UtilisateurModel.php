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

    public function getAllUser(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Utilisateur','u')
            ->innerJoin('u','Entreprise','e','e.id_entreprise=u.id_entreprise')
            ->where('u.droit!= "Droit_Admin"');

        return $queryBuilder->execute()->fetchAll();
    }

    public function deleteUser($idU)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('Utilisateur')
            ->where('id_utilisateur = :id_utilisateur')
            ->setParameter('id_utilisateur',(int)$idU)
        ;
        return $queryBuilder->execute();

    }

    public function getUser($idU)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Utilisateur', 'u')
            ->where('u.id_utilisateur= ?')
            ->innerJoin('u', 'Entreprise', 'e', 'u.id_Entreprise=e.id_Entreprise')
            ->innerJoin('e', 'TypeEntreprise', 'te', 'e.id_typeEntreprise=te.id_typeEntreprise')
            ->innerJoin('e', 'LocalisationEntreprise', 'l', 'l.id_localisation=e.id_localisation')
            ->setParameter(0,$idU);
        ;
        return $queryBuilder->execute()->fetch();
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