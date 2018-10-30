<?php

session_start();

$dsn = "mysql:host=localhost; dbname=e_shop";
$log = "root";
$pwd = "";
$attributes = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdo = new PDO($dsn, $log, $pwd, $attributes);

//> Déclaration des variables
$msg = "";
$contenu = "";
$page = (!empty($page)) ? $page : "Eshop.com";
$seo_description = (!empty($seo_description)) ? $seo_description : "";
# Ici, nous mettons toutes les chances de notre côté pour éviter d'afficher des erreurs. Nous déclarons donc nos variables, mais si $page et $seo sont déclarés avant l'appel de l'init.php, nous faisons en sorte de conserver les valeurs précédemment définies.


//> Déclaration de constantesC:\xampp\htdocs\\eshop_projet\eshop-master
define('RACINE', $_SERVER['DOCUMENT_ROOT'] . '/Back/eshop_project1/eshop_projet/');
define('URL', "http://localhost/Back/eshop_project1/eshop_projet/");

//> Déclaration de constantes  C:\xampp\htdocs\back\eshop_project\eshop_projet
define('RACINE', $_SERVER['DOCUMENT_ROOT'] . '/back/eshop_project/eshop_projet/');
define('URL', "http://localhost/back/eshop_project/eshop_projet/");

# Je me facilite la vie en déclarant en constante le chemin vers mes fichiers ainsi que l'URL de mon site. Si jamais j'appelle ce chemin en dur ou l'URL en dur dans mon site, je n'aurais plus besoin d'aller dans tous mes fichiers modifier les valeurs. Tout ce travail sera contenu dans les constantes déclarées ci-dessus