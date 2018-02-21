<?php
/**
 * Created by PhpStorm.
 * User: SOR
 * Date: 21/02/2018
 * Time: 00:25
 */
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class ConcerneModel{

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function insertConcerne($idCom,$idProd,$quant){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('Concerne')
            ->values([
                'id_commande' => '?',
                'id_produits' => '?',
                'quantite' => '?'
            ])
            ->setParameter(0, $idCom)
            ->setParameter(1, $idProd)
            ->setParameter(2, $quant)
        ;
        echo $queryBuilder;
        return $queryBuilder->execute();

    }

    public function deleteConcerne($idCon){

            $queryBuilder = new QueryBuilder($this->db);
            $queryBuilder
                ->delete('Concerne')
                ->where('id_commande = :id_commande')
                ->setParameter('id_commande',(int)$idCon)
            ;
            return $queryBuilder->execute();


    }

    public function updateConcerne($idCom,$idProd,$quantite){

    }

    public function getConcerne($idCommande)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.quantite,c.id_produits,e.libelle_entreprise,p.heure_fin_vente,l.adresse,co.dates,co.prix_commande,p.prix_produits,p.libelle_produits,co.libelle_commande')
            ->from('Concerne', 'c')
            ->where('c.id_commande = :id_commande')
            ->setParameter('id_commande',(int)$idCommande)
            ->innerJoin('c', 'Commande', 'co', 'c.id_commande=co.id_commande')
            ->innerJoin('c', 'Produits', 'p', 'p.id_produits=c.id_produits')
            ->innerJoin('p', 'Utilisateur', 'u', 'u.id_utilisateur=u.id_utilisateur')
            ->innerJoin('u', 'Entreprise', 'e', 'u.id_entreprise=e.id_entreprise')
            ->innerJoin('e', 'LocalisationEntreprise', 'l', 'l.id_localisation=e.id_localisation')
            ->addOrderBy('co.dates', 'DESC');
        return $queryBuilder->execute()->fetch();
    }
}