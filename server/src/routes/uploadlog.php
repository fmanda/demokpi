<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require_once '../src/classes/DB.php';
require_once '../src/models/ModelUsers.php';

$app->get('/uploadlog', function ($request, $response) {
  try{
    // $data = ModelUsers::retrieveList();
    $str = 'select a.*, b.username , c.deptcode , c.deptname
      from upload_log a
      left join users b on a.user_id = b.id
      left join department c on c.id = a.department_id
      order by a.upload_date desc limit 100 ';

    $data = DB::openQuery($str);
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
