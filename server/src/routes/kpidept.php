<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

require '../vendor/autoload.php';
require_once '../src/classes/DB.php';
require_once '../src/models/ModelKPIDept.php';
require_once '../src/models/ModelDepartment.php';
require_once '../src/models/ModelUploadLog.php';

// $container = new Container();
// $container->set('upload_directory', __DIR__ . '/uploads');

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

$app->post('/kpidept_upload_ml/{yearperiod}/{deptid}/{subcode}/{level}', function(Request $request, Response $response) {
	try{
		return executeUploadFile($request, $response, false);
	}catch(Exception $e){
		$msg = $e->getMessage();
		$response->getBody()->write($msg . ' directory ' . $directory);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});

$app->get('/filelistml/{yearperiod}/{deptid}/{subcode}/{level}', function(Request $request, Response $response) {
	try{
		$str = 'select id, filename , filepath ' // concat('http://127.0.0.1/file/' , id) as link_id
				.' from upload_log '
				.' where yearperiod = '. $request->getAttribute('yearperiod')
				.' and department_id = '. $request->getAttribute('deptid')
				.' and ml_subarea = '. "'" . $request->getAttribute('subcode') . "'"
				.' and level_id = '. $request->getAttribute('level');

    $data = DB::openQuery($str);
    $json = json_encode($data);
    $response->getBody()->write($json);
		return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
	}catch(Exception $e){
		$msg = $e->getMessage();
		$response->getBody()->write($msg . ' directory ' . $directory);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});

$app->get('/filelistkpi/{yearperiod}/{deptid}/{subcode}/{level}', function(Request $request, Response $response) {
	try{
		$str = 'select id, filename , filepath ' // concat('http://127.0.0.1/file/' , id) as link_id
				.' from upload_log '
				.' where yearperiod = '. $request->getAttribute('yearperiod')
				.' and department_id = '. $request->getAttribute('deptid')
				.' and kpi_subarea = '. "'" . $request->getAttribute('subcode') . "'"
				.' and level_id = '. $request->getAttribute('level');

    $data = DB::openQuery($str);
    $json = json_encode($data);
    $response->getBody()->write($json);
		return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
	}catch(Exception $e){
		$msg = $e->getMessage();
		$response->getBody()->write($msg . ' directory ' . $directory);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});

$app->post('/kpidept_upload_kpi/{yearperiod}/{deptid}/{subcode}/{level}', function(Request $request, Response $response) {
	try{
		return executeUploadFile($request, $response, true);
	}catch(Exception $e){
		$msg = $e->getMessage();
		$response->getBody()->write($msg . ' directory ' . $directory);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});

function executeUploadFile($request, $response, $isKPI){
	$config = parse_ini_file("../src/config.ini");
	$directory =  $config["upload_directory"];

	$deptid = $request->getAttribute('deptid');
	$level = $request->getAttribute('level');
	$subcode = $request->getAttribute('subcode');
	$yearperiod = $request->getAttribute('yearperiod');

	$dept = ModelDepartment::retrieveHeader($deptid);
	$deptcode = $dept->deptcode;

	$directory = $directory . DIRECTORY_SEPARATOR . $yearperiod
		. DIRECTORY_SEPARATOR . $deptcode
		. DIRECTORY_SEPARATOR . $subcode;

	if (!file_exists($directory)) {
		mkdir($directory, 0777, true);
	}

	$uploadedFiles = $request->getUploadedFiles();
	foreach ($uploadedFiles as $uploadedFile) {
		if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
			$filename = moveUploadedFile($directory, $uploadedFile);
			$response->getBody()->write('uploaded ' . $directory . DIRECTORY_SEPARATOR . $filename . '; ');
			$obj = new stdClass();
			$obj->filename = $filename;
			$obj->directory = $directory;
			$obj->filepath = $directory . DIRECTORY_SEPARATOR . $filename;
			$obj->yearperiod = $yearperiod;
			if ($isKPI){
				$obj->ml_kpiarea = $subcode;
			}else{
				$obj->ml_subarea = $subcode;
			}
			$obj->level_id = $level;
			$obj->department_id = $dept->id;
			$obj->user_id = 1;
			ModelUploadLog::saveToDB($obj);
		}
	}

	return $response;
}



function moveUploadedFile($directory, UploadedFileInterface $uploadedFile)
{
	$filename = $uploadedFile->getClientFilename();
	$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
	return $filename;
}
