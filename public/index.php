<?php
declare (strict_types = 1);

use MyApp\Routing\Router;
use MyApp\Service\DependencyContainer;
//use Symfony\Component\ErrorHandler\Debug;
//use Symfony\Component\ErrorHandler\ErrorHandler;

require_once __DIR__ . '/../vendor/autoload.php';

//ErrorHandler::register(); // Active le gestionnaire d'erreurs
//Debug::enable();

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env.local');
$dotenv->load();

$container = new DependencyContainer();
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);
$router = new Router($container);
$router->route($twig);
