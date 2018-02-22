<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;   // pour utiliser request
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;
use App\Model\EntrepriseModel;

use App\Model\UtilisateurModel;


class UtilisateurController implements ControllerProviderInterface
{

    private $utilisateurModel;

    public function index(Application $app) {
        return $this->connexionUser($app);
    }

    public function connexionUser(Application $app)
    {
        return $app["twig"]->render('login.html.twig');
    }

    public function validFormConnexionUser(Application $app, Request $req)
    {

        $app['session']->clear();
        $donnees['login']=$req->get('login');
        $donnees['password']=$req->get('password');

        $this->utilisateurModel = new UtilisateurModel($app);
        $data=$this->utilisateurModel->verif_login_mdp_Utilisateur($donnees['login'],$donnees['password']);

        if($data != NULL)
        {
            $app['session']->set('nom_utilisateur', $data['nom_utilisateur']);  //dans twig {{ app.session.get('roles') }}
            $app['session']->set('prenom_utilisateur', $data['prenom_utilisateur']);
            $app['session']->set('droit', $data['droit']);
            $app['session']->set('adresseMail_utilisateur', $data['adresseMail_utilisateur']);
            $app['session']->set('logged', 1);
            $app['session']->set('id_utilisateur', $data['id_utilisateur']);

            return $app->redirect($app["url_generator"]->generate("accueil"));
        }
        else
        {
            $app['session']->set('erreur','mot de passe ou login incorrect');
            return $app["twig"]->render('login.html.twig');
        }
    }
    public function deconnexionSession(Application $app)
    {
        $app['session']->clear();
        $app['session']->getFlashBag()->add('msg', 'vous êtes déconnecté');
        return $app->redirect($app["url_generator"]->generate("accueil"));
    }

    public function showChoixProfil(Application $app){
        return $app["twig"]->render('Utilisateur/choixProfilClient.html.twig');
    }

    public function getAllUtilisateur(Application $app){
        $this->utilisateurModel= new UtilisateurModel($app);
         $data = $this->utilisateurModel->getAllUser();
        return $app["twig"]->render('Admin/Utilisateur/showUtilisateur.html.twig',['data'=>$data]);
    }

    public function supprimerUser(Application $app, Request $req){
        $idU=$app->escape($req->get('id_utilisateur'));
        $this->utilisateurModel= new UtilisateurModel($app);
        $this->utilisateurModel->deleteUser($idU);
        return $app->redirect($app["url_generator"]->generate("user.showUser"));

    }


    public function modifierUser(Application $app, Request $req){
        $idU=$app->escape($req->get('id_utilisateur'));
        $this->utilisateurModel= new UtilisateurModel($app);
      $data= $this->utilisateurModel->getUser($idU);
        return $app["twig"]->render('Admin/Utilisateur/editUtilisateur.html.twig',['donnees'=>$data]);

    }

    public function validModifUser(Application $app){
        if (isset($_POST['name']) and isset($_POST['prenom']) and isset($_POST['adresse']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['ville'])) {
            $donnees = [
                'id_utilisateur' => htmlentities($_POST['id_utilisateur']),
                'nom_utilisateur' => htmlspecialchars($_POST['name']),                    // echapper les entrées
                'prenom_utilisateur' => htmlspecialchars($_POST['prenom']),
                'adresse_utilisateur' => htmlspecialchars($_POST['adresse']),
                'adresseMail_utilisateur' => htmlentities($_POST['email']),
                'password_utilisateur' => htmlentities(md5($_POST['password'])),
                'ville' => htmlentities($_POST['ville']),
            ];
            echo "coucou";
            $this->utilisateurModel = new UtilisateurModel($app);
            $this->utilisateurModel->updateUserAdmin($donnees);

            return $app->redirect($app["url_generator"]->generate("user.showUser"));
        }

    }

    public function modifPUser(Application $app ,Request $req){
        $idU=$app->escape($req->get('id_utilisateur'));
        $this->utilisateurModel= new UtilisateurModel($app);
        $data= $this->utilisateurModel->getUser($idU);
        return $app["twig"]->render('Admin/Utilisateur/editPUtilisateur.html.twig',['donnees'=>$data]);

    }

    public function validPModifUser(Application $app){
        if ( isset($_POST['password']) and isset($_POST['id_utilisateur'])) {

            $this->utilisateurModel = new UtilisateurModel($app);
            $this->utilisateurModel->updateMDP($_POST['id_utilisateur'],$_POST['password']);

            return $app->redirect($app["url_generator"]->generate("user.showUser"));
        }

    }

    public function editeEntrepriseUser(Application $app,Request $req){
        $idU=$app->escape($req->get('id_utilisateur'));
        $this->utilisateurModel = new UtilisateurModel($app);
        $data=$this->utilisateurModel->getUserEntreprise($idU);
        return $app["twig"]->render('Admin/Utilisateur/editEntrepriseUser.html.twig',['donnees'=>$data]);

    }

    public function editFormEntrepriseUser(Application $app,Request $req){

        /* A remplir avec deux listes déroulante une pour ville et une pour type*/

    }

    public function inscriptionUtilisateur(Application $app){
        $this->typeEntreprise=new EntrepriseModel($app);
        $typeEntreprise= $this->typeEntreprise->getAllEntreprise();
        return $app["twig"]->render('inscription.html.twig',['data'=>$typeEntreprise]);
    }


    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];
        $controllers->match('/', 'App\Controller\UtilisateurController::index')->bind('user.index');
        $controllers->get('/login', 'App\Controller\UtilisateurController::connexionUser')->bind('user.login');
        $controllers->post('/login', 'App\Controller\UtilisateurController::validFormConnexionUser')->bind('user.validFormlogin');
        $controllers->get('/logout', 'App\Controller\UtilisateurController::deconnexionSession')->bind('user.logout');
        $controllers->get('/showChoixProfil', 'App\Controller\UtilisateurController::showChoixProfil')->bind('user.choix');
        $controllers->get('/showAllUser', 'App\Controller\UtilisateurController::getAllUtilisateur')->bind('user.showUser');
        $controllers->get('/deleteUser', 'App\Controller\UtilisateurController::supprimerUser')->bind('user.delUser');
        $controllers->get('/editUser', 'App\Controller\UtilisateurController::modifierUser')->bind('user.editUser');
        $controllers->post('/editFromUser', 'App\Controller\UtilisateurController::validModifUser')->bind('user.validModifUser');
        $controllers->get('/editPUser', 'App\Controller\UtilisateurController::modifPUser')->bind('user.editP');
        $controllers->post('/editFromPUser', 'App\Controller\UtilisateurController::validPModifUser')->bind('user.editFromP');
        $controllers->get('/editEntrUser', 'App\Controller\UtilisateurController::editeEntrepriseUser')->bind('user.editEntrUser');
        $controllers->post('/editEntFromUser', 'App\Controller\UtilisateurController::editFormEntrepriseUser')->bind('user.editFromEntreUser');
        $controllers->get('/inscription', 'App\Controller\UtilisateurController::inscriptionUtilisateur')->bind('user.inscription');


        return $controllers;
    }
}