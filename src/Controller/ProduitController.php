<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\ProduitModel;

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
        $produits = $this->produitModel->getAllProduits();
        //print_r($produits);
        return $app["twig"]->render('/Utilisateur/showProduitsClient.html.twig',['data'=>$produits]);
    }


    public function showProduitsA(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $produits = $this->produitModel->getAllProduits();

        return $app["twig"]->render('/Admin/Produit/showProduitsAdmin.html.twig',['data'=>$produits]);
    }
    //Fonction a utiliser aussi pour vendeur
    public function deleteProduits(Application $app, Request $req) {

        $id=$app->escape($req->get('idProduit'));
        $this->produitModel = new ProduitModel($app);
        $this->produitModel->deleteProduit($id);
        if ( $app['session']->get('droit') == 'Droit_Admin') {
            echo "coucou";
            return $app->redirect($app["url_generator"]->generate("produit.showProduitsA"));
        }
        else{
            return "redirection du vendeur.";
        }
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
        $controllers->get('/showAdmin', 'App\Controller\produitController::showProduitsA')->bind('produit.showProduitsA');
        $controllers->get('/detail', 'App\Controller\produitController::detailsProduit')->bind('produit.detail');
        $controllers->get('/deleteP', 'App\Controller\produitController::deleteProduits')->bind('produit.deleteProduit');

        return $controllers;
    }
}
