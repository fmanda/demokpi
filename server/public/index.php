<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath('/public');

// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });
$app->addErrorMiddleware(true, false, false);

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH');
});


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Server Ready !");
    return $response;
});

require '../src/routes/test.php';
require '../src/routes/department.php';
require '../src/routes/kpidept.php';
require '../src/routes/directory.php';
require '../src/routes/users.php';
require '../src/routes/uploadlog.php';

// $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'], '/{routes:.+}', function ($request, $response) {
//     throw new HttpNotFoundException($request);
// });

// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });

$app->run();
