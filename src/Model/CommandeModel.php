<?php
/**
 * Created by PhpStorm.
 * User: SOR
 * Date: 21/02/2018
 * Time: 00:23
 */
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class CommandeModel{

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllCommande(Application $app){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Commande', 'c')
            ->where('c.id_utilisateur= ?')
            ->setParameter(0,$app['session']->get('id_utilisateur'))
            ->innerJoin('c', 'Utilisateur', 'u', 'c.id_utilisateur=u.id_utilisateur')
            ->addOrderBy('c.dates', 'DESC');
        return $queryBuilder->execute()->fetchAll();

    }

    public function getAllCommandeA(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Commande', 'c')
            ->innerJoin('c', 'Utilisateur', 'u', 'c.id_utilisateur=u.id_utilisateur')
            ->addOrderBy('c.dates', 'DESC');
        return $queryBuilder->execute()->fetchAll();

    }

    public function getIdCommandeByData($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id_commande')
            ->from('commande ','c')
            ->innerJoin('c', 'Utilisateur', 'u', 'c.id_utilisateur=u.id_utilisateur')
            ->where('c.libelle_commande= :lib and c.prix_commande = :p and c.dates=:d and c.id_utilisateur=:u')
            ->setParameter('lib', $donnees['libeCom'])
            ->setParameter('p', $donnees['prixCom'])
             ->setParameter('d', $donnees['dateCom'])
            ->setParameter('u', $donnees['idUser'])
        ;

        return $queryBuilder->execute()->fetch();

    }
    public function insertCommande($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('Commande')
            ->values([
                'libelle_commande' => '?',
                'prix_commande' => '?',
                'dates' => '?',
                'id_utilisateur' => '?'

            ])
            ->setParameter(0, $donnees['libeCom'])
            ->setParameter(1, $donnees['prixCom'])
            ->setParameter(2, $donnees['dateCom'])
            ->setParameter(3, $donnees['idUser'])
        ;
        echo $queryBuilder;
        return $queryBuilder->execute();

    }

    public function updateCommande($donnees){

    }

    public function DeleteCommande($idCom)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('Commande')
            ->where('id_commande = :id_commande')
            ->setParameter('id_commande',(int)$idCom)
        ;
        return $queryBuilder->execute();
    }

}