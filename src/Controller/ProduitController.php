<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\ProduitModel;
use App\Model\TypeProduitModel;
use App\Model\PanierModel;
use App\Model\CompteModel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;


class ProduitController implements ControllerProviderInterface
{
    private $produitModel;
    private $typeProduitModel;


    public function index(Application $app) {
        return $this->showProduits($app);
    }

    public function showProduitsC(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $produits = $this->produitModel->getAllProduits($app['session']->get('id_utilisateur'));
        //print_r($produits);
        return $app["twig"]->render('/Utilisateur/showProduitsClient.html.twig',['data'=>$produits]);
    }

    public function detailsProduit(Application $app,Request $req){

        $idP=$app->escape($req->get('idProduit'));

        $this->produitModel=new ProduitModel($app);
        $produit=$this->produitModel->getProduit($idP);
        return $app["twig"]->render('/Utilisateur/detailProduit.html.twig',['produit'=>$produit]);

    }



    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\produitController::index')->bind('produit.index');
        $controllers->get('/show', 'App\Controller\produitController::showProduitsC')->bind('produit.showProduitsC');
        $controllers->get('/detail', 'App\Controller\produitController::detailsProduit')->bind('produit.detail');



        return $controllers;
    }
}
