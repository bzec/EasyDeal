<?php
namespace App\Controller;

use DateTime;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0
use App\Model\CommandeModel;
use App\Model\ConcerneModel;
use App\Model\ProduitModel;
use Symfony\Component\HttpFoundation\Request;

class CommandeController implements ControllerProviderInterface
{
    private $produitModel;
    private $commandeModel;
    private $concerneModel;

    public function index(Application $app)
    {
        return $app["twig"]->render("accueil.html.twig");
    }

    public function addCommande(Application $app, $idP ,$qte){

        $this->produitModel = new ProduitModel($app);
        $this->commandeModel= new CommandeModel($app);
        $this->concerneModel=new  ConcerneModel($app);

        $donnees = $this->produitModel->getProduit($idP);
        $date=new DateTime();
        $donnees['dateCom']= $date->format('Y-m-d H:i:s');
        $donnees['prixCom']=$donnees['prix_produits']*$qte;
        $donnees['idUser']= $app['session']->get('id_utilisateur');
        $donnees['libeCom']='commande de '.$donnees['libelle_produits'];
        $this->commandeModel->insertCommande($donnees);

        $d=$this->commandeModel->getIdCommandebyData($donnees);
        $idCom= $d['id_commande'];
        $this->concerneModel->insertConcerne($idCom,$idP,$qte);

        $this->produitModel->updateStockProduit($idP,$donnees['stockProduit']-$qte);
        return $app->redirect($app["url_generator"]->generate("produit.showProduitsC"));


    }

    public function deleteCommande(Application $app,Request $req){
        $idCom=$app->escape($req->get('idCommande'));
        $this->produitModel = new ProduitModel($app);
        $this->commandeModel= new CommandeModel($app);
        $this->concerneModel=new  ConcerneModel($app);

        $data=$this->concerneModel->getConcerne($idCom);
        $q=$this->produitModel->getProduit($data['id_produits']);
        echo $data['quantite'];
        $data['quantite']+=$q['stockProduit'];
        $this->produitModel->updateStockProduit($data['id_produits'],$data['quantite']);
        $this->concerneModel->deleteConcerne($idCom);
        $this->commandeModel->DeleteCommande($idCom);
        return $app->redirect($app["url_generator"]->generate("commande.mesCommandes"));

    }

    public function showCommande(Application $app){
        $this->commandeModel=new CommandeModel($app);
        $data=$this->commandeModel->getAllCommande($app);
        return $app["twig"]->render('Utilisateur/showCommandeClient.html.twig' ,['data'=>$data]);
    }


    public function showDetailCommande(Application $app, Request $req){
        $idCommande=$app->escape($req->get('idCommande'));
        $this->concerneModel=new ConcerneModel($app);
        $data=$this->concerneModel->getConcerne($idCommande);

        return $app["twig"]->render('Utilisateur/showDetailsCommandeClient.html.twig' ,['data'=>$data]);
    }

    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
        $index->match("/add/{idP}/{qte}", 'App\Controller\CommandeController::addCommande')->bind('commande.add');
        $index->get('/showCommande', 'App\Controller\CommandeController::showCommande')->bind('commande.mesCommandes');
        $index->get('/showDetailCommande', 'App\Controller\CommandeController::showDetailCommande')->bind('commande.detailsCommande');
        $index->get('/deleteCommande', 'App\Controller\CommandeController::deleteCommande')->bind('commande.del');
        return $index;
    }


}
