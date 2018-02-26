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

class EntrepriseModel
{

    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllEntreprise(){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('Entreprise', 'e');
        return $queryBuilder->execute()->fetchAll();
    }

    public function insertEntreprise($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('Entreprise')
            ->values([
                'libelle_entreprise' => '?',
                'id_localisation' => '?',
                'id_typeEntreprise' => '?',
                'siren' => '?'
            ])
            ->setParameter(0, $donnees['libelle_entreprise'])
            ->setParameter(1, $donnees['id_localisation'])
            ->setParameter(2, $donnees['id_typeEntreprise'])
            ->setParameter(3, $donnees['Siren'])
        ;
        echo $queryBuilder;
        return $queryBuilder->execute();

    }

    public function getEntreprise($donnee)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_entreprise')
            ->from('Entreprise', 'e')
            ->where(' e.libelle_entreprise= ? and e.id_typeEntreprise= ? and siren=? ')
            ->setParameter(0,$donnee['libelle_entreprise'])
            ->setParameter(1,$donnee['id_typeEntreprise'])
            ->setParameter(2,$donnee['Siren']);
        return $queryBuilder->execute()->fetch();
    }

}
