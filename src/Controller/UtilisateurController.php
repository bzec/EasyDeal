<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;   // pour utiliser request
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

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



    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];
        $controllers->match('/', 'App\Controller\UtilisateurController::index')->bind('user.index');
        $controllers->get('/login', 'App\Controller\UtilisateurController::connexionUser')->bind('user.login');
        $controllers->post('/login', 'App\Controller\UtilisateurController::validFormConnexionUser')->bind('user.validFormlogin');
        $controllers->get('/logout', 'App\Controller\UtilisateurController::deconnexionSession')->bind('user.logout');
        $controllers->get('/showChoixProfil', 'App\Controller\UtilisateurController::showChoixProfil')->bind('user.choix');

        return $controllers;
    }
}