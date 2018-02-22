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

}
