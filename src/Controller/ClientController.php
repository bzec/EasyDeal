<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;   // pour utiliser request
use App\Model\PanierModel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

use App\Model\UtilisateurModel;
use App\Model\CompteModel;

class ClientController implements ControllerProviderInterface
{
   private $clientModel;
   private $comteModel;

    public function index(Application $app) {

        return $this->showClient($app);
    }





    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\ClientController::index')->bind('client.index');
        $controllers->get('/addClient/{id_utilisitateur}','App\Controller\ClientController::addClient')->bind('client.add');
        $controllers->post('/addClient/ ','App\Controller\ClientController::addFromClient')->bind('client.addFromAdd');

        return $controllers;
    }
}
