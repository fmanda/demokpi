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

$app->post('/kpidept_upload/{yearperiod}/{deptcode}/{subcode}', function(Request $request, Response $response) {
	try{
		$config = parse_ini_file("../src/config.ini");
		$directory =  $config["upload_directory"];

		$deptcode = $request->getAttribute('deptcode');
		$subcode = $request->getAttribute('subcode');
		$yearperiod = $request->getAttribute('yearperiod');

		$tmp = DB::openQuery("select * from " .ModelDepartment::getTableName()." where deptcode='". $deptcode. "'");
		if (isset($obj[0])) $dept = $tmp[0];

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
        $response->getBody()->write('uploaded ' . $directory . DIRECTORY_SEPARATOR . $filename . '<br/>');

				$obj = new stdClass();
				$obj->filename = $filename;
				$obj->directory = str_replace($directory, DIRECTORY_SEPARATOR ,  DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) ;
				$obj->yearperiod = $yearperiod;
				$obj->ml_subarea = $subcode;

				if (isset($dept)){
					$obj->department_id = $dept->id;
				}

				$obj->user_id = 1;
				ModelUploadLog::saveToDB($obj);
      }
    }

		return $response;
	}catch(Exception $e){
		$msg = $e->getMessage();
		$response->getBody()->write($msg . ' directory ' . $directory);
		return $response->withStatus(500)
			->withHeader('Content-Type', 'text/html');
	}
});


function moveUploadedFile($directory, UploadedFileInterface $uploadedFile)
{
	$filename = $uploadedFile->getClientFilename();
	$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
	return $filename;
}
