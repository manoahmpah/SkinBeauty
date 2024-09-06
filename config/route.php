<?php

// routes.php

$routes = [
    '/' => 'HomeController@index',
    '/user/profile' => 'UserController@profile',
    '/login' => 'AuthController@login',
    '/logout' => 'AuthController@logout',
    '/products' => 'ProductController@index',
    '/products/show' => 'ProductController@show',
];

function handleRequest($uri)
{
    global $routes;

    if (array_key_exists($uri, $routes)) {
        list($controller, $method) = explode('@', $routes[$uri]);

        $controllerFile = 'app/controllers/' . $controller . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerInstance = new $controller;
            $controllerInstance->$method();
        } else {
            echo "Erreur 404 : Contrôleur non trouvé.";
        }
    } else {
        echo "Erreur 404 : Page non trouvée.";
    }
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
handleRequest($uri);