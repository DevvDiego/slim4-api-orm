<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Middleware\ValidationMiddleware;
use Slim\Factory\AppFactory;

Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->load();


$container = require __DIR__ . "/../src/container.php";

AppFactory::setContainer($container);
$app = AppFactory::create();

// $app->setBasePath('');

$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    
    return $response
        ->withHeader('Access-Control-Allow-Origin', $_ENV["ALLOWED_ORIGINS"])
        ->withHeader('Access-Control-Allow-Headers', $_ENV["ALLOWED_HEADERS"])
        ->withHeader('Access-Control-Allow-Methods', $_ENV["ALLOWED_METHODS"]);
});




//Auth middleware injects the decoded token in the request here
$app->group('/admin', function ($group) use ($app){


    // $group->get('/verify', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
    //     $user = \App\Auth\Auth::user();
        
    //     $response->getBody()->write(json_encode([
    //         "message" => "Hola {$user->email}, tu ID es {$user->sub}",
    //         "role"    => $user->role ?? null
    //     ]));

    //     return $response->withHeader('Content-Type', 'application/json');

})->add(\App\Middleware\AuthMiddleware::class);


$app->get("/session", \App\Controllers\SessionController::class . ":show");





$app->post('/login', \App\Controllers\AuthController::class . ":login")
    ->add(new ValidationMiddleware(["email", "password"]));


/* $app->get('/users/{id}', \App\Controllers\UserController::class . ':showUser');

$app->get('/tickets/{id}', \App\Controllers\TicketController::class . ':showTicket');
 */

$app->post('/users', \App\Controllers\UserController::class . ":new")
    ->add(new ValidationMiddleware(["name", "email", "password", "role"]));

$app->get('/db', \App\Database\SchemaManager::class . ":sync");


$app->run();

?>
