<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require_once '../src/classes/DB.php';
require_once '../src/models/ModelKPIDept.php';

$app->get('/kpidept/{dept_id}/{year}', function ($request, $response, $args) {
	try{
    $deptid = $request->getAttribute('dept_id');
    $year = $request->getAttribute('year');
    $data = ModelKPIDept::generate($deptid, $year);
    $json = json_encode($data);
    $response->getBody()->write($json);
		return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
	}catch(Exception $e){
    $msg = $e->getMessage();
    $response->getBody()->write($msg);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});
