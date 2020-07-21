<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';
require_once '../src/classes/DB.php';
require_once '../src/models/ModelUploadLog.php';

use GuzzleHttp\Psr7\LazyOpenStream;

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

//


$app->get('/downloadfile/{id}', function ($request, $response) {
  try{
    $id = $request->getAttribute('id');
    $obj = ModelUploadLog::retrieve($id);
    $file = $obj->filepath;
    // $file = 'upload/2020/L3/L3_17/rainbow_six_siege_outbreak_4k_8k-wide.jpg';
    $newStream = new LazyOpenStream($file, 'r');
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/force-download')
      ->withHeader('Content-Type', 'application/octet-stream')
      ->withHeader('Content-Type', 'application/download')
      ->withHeader('Content-Description', 'File Transfer')
      ->withHeader('Content-Transfer-Encoding', 'binary')
      ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
      ->withHeader('Expires', '0')
      ->withHeader('Content-Length', filesize($file))
      ->withHeader('Cache-Control', 'must-revalidate')
      ->withHeader('Pragma', 'public')
      ->withBody($newStream);
    // return $response;
  }catch(Exception $e){
    $msg = $e->getMessage();
    $response->getBody()->write($msg);
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'text/html');
  }

});



$app->get('/testimage', function ($request, $response) {
  try{
    // $file = 'upload\2020\L3\L3_17\rainbow_six_siege_outbreak_4k_8k-wide.jpg';
    $file = 'upload/2020/L3/L3_17/rainbow_six_siege_outbreak_4k_8k-wide.jpg';
    // $file = 'test.jpg';
    $newStream = new LazyOpenStream($file, 'r');
    // $response = $response->withBody($newStream);
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/force-download')
      ->withHeader('Content-Type', 'application/octet-stream')
      ->withHeader('Content-Type', 'application/download')
      ->withHeader('Content-Description', 'File Transfer')
      ->withHeader('Content-Transfer-Encoding', 'binary')
      ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
      ->withHeader('Expires', '0')
      ->withHeader('Content-Length', filesize($file))
      ->withHeader('Cache-Control', 'must-revalidate')
      ->withHeader('Pragma', 'public')
      ->withBody($newStream);
    // return $response;
  }catch(Exception $e){
    $msg = $e->getMessage();
    $response->getBody()->write($msg);
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

// $app->get('/testimage', function ($request, $response) {
//   try{
//     $file = 'upload\2020\L3\L3_17\rainbow_six_siege_outbreak_4k_8k-wide.jpg';
//     $openFile = fopen($file, 'rb');
//     $stream = new Stream($openFile);
    // return $response->withStatus(200)
    //   ->withHeader('Content-Type', 'application/force-download')
    //   ->withHeader('Content-Type', 'application/octet-stream')
    //   ->withHeader('Content-Type', 'application/download')
    //   ->withHeader('Content-Description', 'File Transfer')
    //   ->withHeader('Content-Transfer-Encoding', 'binary')
    //   ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
    //   ->withHeader('Expires', '0')
    //   ->withHeader('Content-Length', filesize($file))
    //   ->withHeader('Cache-Control', 'must-revalidate')
    //   ->withHeader('Pragma', 'public')
    //   ->withBody($stream);
//   }catch(Exception $e){
//     $msg = $e->getMessage();
//     $response->getBody()->write($msg);
//     return $response->withStatus(500)
//       ->withHeader('Content-Type', 'text/html');
//   }
//
// });
