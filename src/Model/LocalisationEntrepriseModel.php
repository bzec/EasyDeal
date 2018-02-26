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

class LocalisationEntrepriseModel
{

    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getLocalisationEntreprise($adresse){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('*')
            ->from('LocalisationEntreprise')
        ->where("adresse = \"".$adresse."\"");
        return $queryBuilder->execute()->fetch();
    }

    public function insertLocalisationEntreprise($adress)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('LocalisationEntreprise')
            ->values([
                'adresse' => '?'
            ])
            ->setParameter(0, $adress)

        ;
        echo $queryBuilder;
        return $queryBuilder->execute();

    }

}
