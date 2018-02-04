<?php
//***************************************
// Montage des controleurs sur le routeur

$app->mount("/", new App\Controller\IndexController($app));

$app->mount("/user", new App\Controller\UtilisateurController($app));

$app->mount("/produit", new App\Controller\ProduitController($app));
/*
$app->mount("/connexion", new App\Controller\CompteController($app));
$app->mount("/Client", new App\Controller\ClientController($app));
$app->mount("/Vendeur", new App\Controller\VendeurController($app));
$app->mount("/panier", new App\Controller\PanierController($app));
*/