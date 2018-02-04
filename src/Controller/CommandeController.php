<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // modif version 2.0


class CommandeController implements ControllerProviderInterface
{
    public function index(Application $app)
    {
        return $app["twig"]->render("accueil.html.twig");
    }

    /*public function addPanier(Application $app){
     return ;
    )
    */
    public function connect(Application $app)
    {
        $index = $app['controllers_factory'];
       // $index->match("/", 'App\Controller\CommandeController::index')->bind('accueil');
        return $index;
    }


}
