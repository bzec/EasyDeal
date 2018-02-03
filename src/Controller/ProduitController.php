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
    private $compteModel;
    private $panierModel;

    public function index(Application $app) {
        return $this->showProduits($app);
    }

    public function showProduits(Application $app) {
        $this->produitModel = new ProduitModel($app);
        $produits = $this->produitModel->getAllProduits();
        return $app["twig"]->render('backOff/Produit/showProduits.html.twig',['data'=>$produits]);
    }




    public function addProduit(Application $app) {
        $this->typeProduitModel = new TypeProduitModel($app);
        $typeProduits = $this->typeProduitModel->getAllTypeProduits();
      //  dump($typeProduits);
        return $app["twig"]->render('backOff/Produit/addProduit.html.twig',['typeProduits'=>$typeProduits]);
    }

    public function validFormAddProduit(Application $app, Request $req) {
        if (isset($_POST['libelle_produits']) and isset($_POST['prix_produits']) and isset($_POST['quantite_produits']) and isset($_POST['heure_debut_vente']) and isset($_POST['heure_fin_vente']) and isset($_POST['id_typeProduits'])  ) {
            $donnees = [
                'libelle_produits' => htmlspecialchars($_POST['libelle_produits']),                    // echapper les entrées
                'prix_produits' => htmlspecialchars($req->get('prix_produits')),
                'quantite_produits' => htmlspecialchars($req->get('quantite_produits')),
                'heure_debut_vente' => htmlspecialchars($req->get('heure_debut_vente')),
                'heure_fin_vente' => $app->escape($req->get('heure_fin_vente')),
                'id_typeProduits' => $app->escape($req->get('id_typeProduits'))
            ];
            //verifier date
            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_produits']))) $erreurs['libelle_produits']='nom du produit composé de 2 lettres minimum';
            if(! is_numeric($donnees['prix_produits']))$erreurs['prix_produits']='veuillez saisir une valeur';
            if(! is_numeric($donnees['quantite_produits']))$erreurs['quantite_produits']='saisir une valeur numérique';
            //if (! preg_match("/[A-Za-z0-9]{2,}.(jpeg|jpg|png)/",$donnees['photo'])) $erreurs['photo']='nom de fichier incorrect (extension jpeg , jpg ou png)';

            if(! empty($erreurs))
            {
                $this->typeProduitModel = new TypeProduitModel($app);
                $typeProduits = $this->typeProduitModel->getAllTypeProduits();
                return $app["twig"]->render('backOff/Produit/addProduit.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typeProduits'=>$typeProduits]);
            }
            else
            {
                $this->ProduitModel = new ProduitModel($app);
                $this->ProduitModel->insertProduit($donnees);
                return $app->redirect($app["url_generator"]->generate("produit.index"));
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }







    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\produitController::index')->bind('produit.index');
        $controllers->get('/show', 'App\Controller\produitController::showProduits')->bind('produit.showProduits');



        return $controllers;
    }
}
