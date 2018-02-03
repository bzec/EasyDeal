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

class VendeurController implements ControllerProviderInterface
{
    private $vendeurModel;
    private $comteModel;

    public function index(Application $app) {

        return $this->showVendeur($app);
    }


    public function showVendeur(Application $app) {
        $this->vendeurModel=new UtilisateurModel($app);
        $vendeurs=$this->vendeurModel->getAllVendeurs();
        return $app["twig"]->render('backOff/Vendeur/showVendeur.html.twig',['data'=>$vendeurs]);
    }

    public function addVendeur(Application $app,$idProduit){


    }


    public function addFormVendeur(Application $app){


    }

    public function editVendeur(Application $app,$idProduit){

    }


    public function editFormVendeur(Application $app){



    }

    public function deleteVendeur(Application $app,$id)
    {
        $id_utilisateur=$id;
        $this->vendeurModel =new UtilisateurModel($app);
        $this->comteModel =new CompteModel($app);
        $this->comteModel->deleteCompteUtilisateur($id_utilisateur);
        $this->vendeurModel->deleteUtilisateur($id_utilisateur);
        return $app->redirect($app["url_generator"]->generate("vendeur.index"));
    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\VendeurController::index')->bind('vendeur.index');
        $controllers->get('/addClient/{id_utilisitateur}','App\Controller\VendeurController::addVendeur')->bind('vendeur.add');
        $controllers->post('/addClient/ ','App\Controller\VendeurController::addFromVendeur')->bind('vendeur.addFromAdd');

        $controllers->get('/showClient', 'App\Controller\VendeurController::showVendeur')->bind('vendeur.show');

        $controllers->get('/deleteClient/{id}', 'App\Controller\VendeurController::deleteVendeur')->bind('vendeur.delete')->assert('id', '\d+');
        $controllers->delete('/deleteClient', 'App\Controller\VendeurController::validFormDeleteVendeur')->bind('vendeur.validFormDelete');

        return $controllers;
    }
}
