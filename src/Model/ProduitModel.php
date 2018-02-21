<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class ProduitModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses
    public function getAllProduits() {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Produits', 'p')
            ->where('p.stockProduit >0')
            ->innerJoin('p', 'TypeProduits', 't', 'p.id_typeProduits=t.id_typeProduits')
            ->innerJoin('p', 'Utilisateur', 'u', 'p.id_utilisateur=u.id_utilisateur')
            ->innerJoin('p', 'OrigineProduits', 'o', 'p.id_origineProduits=o.id_origineProduits')
            ->addOrderBy('p.id_produits','ASC')
            ->addOrderBy('p.libelle_produits', 'ASC');

        return $queryBuilder->execute()->fetchAll();

    }

    function getProduit($idProduit) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Produits', 'p')
            ->where('p.id_produits= ?')
            ->innerJoin('p', 'TypeProduits', 't', 'p.id_typeProduits=t.id_typeProduits')
            ->innerJoin('p', 'Utilisateur', 'u', 'p.id_utilisateur=u.id_utilisateur')
            ->innerJoin('p', 'OrigineProduits', 'o', 'p.id_origineProduits=o.id_origineProduits')
            ->setParameter(0,$idProduit);
        return $queryBuilder->execute()->fetch();
    }

    public function updateStockProduit($idP,$stock){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('Produits')
            ->set('stockProduit', '?')
            ->where('id_produits= ?')
            ->setParameter(0, $stock)
            ->setParameter(1, $idP);
        return $queryBuilder->execute();
    }

    public function getProduitType($nomTypeProduits){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Produits', 'p')
            ->where('t.libelle_typeProduits= ?')
            ->innerJoin('p', 'TypeProduits', 't', 'p.id_typeProduits=t.id_typeProduits')
            ->setParameter(0,$nomTypeProduits);
        return $queryBuilder->execute()->fetchAll();
    }

}