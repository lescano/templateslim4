<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request; 
use Slim\App;
use Slim\Views\Twig;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

use App\Application\Actions\Auth\RegisterAction;


return function (App $app) {

    $app->group('/users', function (Group $group) {
        
        //get routes
        $group->get('/register', function (Request $request, Response $response, $args) {
            return $this->get('view')->render($response, 'register.twig');
        });

        $group->get('/{id}', function (Request $request, Response $response, $args) {            
            return $this->get('view')->render($response, 'users/profile.twig');
        });


        

        $group->post('/register', function (Request $request, Response $response, $args) {
            $logger = new Logger('register logger');
            $action = new RegisterAction($logger);
            return $action($request, $response, $args);
        });


    });
};
