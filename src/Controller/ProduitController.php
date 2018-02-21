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

    public function showProduitsRe(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $idTypeProduits="restaurant";
        $produits = $this->produitModel->getProduitType($idTypeProduits);
        return $app["twig"]->render('/Utilisateur/showProduitsClient.html.twig',['data'=>$produits]);
    }

    public function showProduitsBo(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $idTypeProduits="boulangerie";
        $produits = $this->produitModel->getProduitType($idTypeProduits);
        return $app["twig"]->render('/Utilisateur/showProduitsClient.html.twig',['data'=>$produits]);
    }

    public function showProduitsPa(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $idTypeProduits="patisserie";
        $produits = $this->produitModel->getProduitType($idTypeProduits);
        return $app["twig"]->render('/Utilisateur/showProduitsClient.html.twig',['data'=>$produits]);
    }

    public function showProduitsCC(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $idTypeProduits="centreCommercial";
        $produits = $this->produitModel->getProduitType($idTypeProduits);
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
        $controllers->get('/show/restaurant', 'App\Controller\produitController::showProduitsRe')->bind('produit.showProduitsRe');
        $controllers->get('/show/boulangerie', 'App\Controller\produitController::showProduitsBo')->bind('produit.showProduitsBo');
        $controllers->get('/show/patisserie', 'App\Controller\produitController::showProduitsPa')->bind('produit.showProduitsPa');
        $controllers->get('/show/centreCommercial', 'App\Controller\produitController::showProduitsCC')->bind('produit.showProduitsCC');

        $controllers->get('/detail', 'App\Controller\produitController::detailsProduit')->bind('produit.detail');

        return $controllers;
    }
}
