<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require_once '../src/classes/DB.php';
require_once '../src/models/ModelUsers.php';

$app->get('/users', function ($request, $response) {
  try{
    // $data = ModelUsers::retrieveList();
    $str = 'select a.id, a.username, a.fullname, b.deptcode ,b.deptname
            from users a
            left join department b on a.department_id = b.id ';

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

$app->get('/users/{id}', function ($request, $response, $args) {
	try{
    $id = $request->getAttribute('id');
    $data = ModelUsers::retrieve($id);
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

$app->post('/users', function ($request, $response) {
	$json = $request->getBody();
	$obj = json_decode($json);
	try{
		ModelUsers::saveToDB($obj);
    $json = json_encode($obj);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
	}catch(Exception $e){
		$msg = $e->getMessage();
    $response->getBody()->write($msg);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}

});

$app->get('/users_delete/{id}', function ($request, $response) {  //if hosting not allowed del
  try{
    $data = ModelUsers::deleteFromDB($request->getAttribute('id'));
		return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
	}catch(Exception $e){
    $msg = $e->getMessage();
    $response->getBody()->write($msg);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});

$app->post('/login', function (Request $request, Response $response, array $args) {
  $config = parse_ini_file("../src/config.ini");
  $input = $request->getParsedBody();
  $username=trim(strip_tags($input['username']));
  $password=trim(strip_tags($input['password']));
  $sql = 'SELECT * FROM users WHERE username=:username AND password=:password';
  $sth = $this->db->prepare($sql);
  $sth->bindParam("username", $username);
  $sth->bindParam("password", $password);
  $sth->execute();
  $user = $sth->fetchObject();
  if(!$user) {
      return $this->response->withJson(['status' => 'error', 'message' => 'These credentials do not match our records username.']);
  }
  $settings = $this->get('settings');
  $token = array(
      'IdUser' =>  $user->IdUser,
      'Username' => $user->Username
  );
  $token = JWT::encode($token, $settings['jwt']['secret'], "HS256");
  return $this->response->withJson(['status' => 'success','data'=>$user, 'token' => $token]);

});
