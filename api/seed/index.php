<?php
require_once '../database/connection.php';
require_once 'method.php';

use Generate\Api\Connection as Con;
use Generate\Api\Migration as Api;

$con = new Con();
$api = new Api();

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $con->start();
        $https = $con->https;

        $jsonModel = file_get_contents($https . "/models/" . $_GET['seed'] . "-model.json");
        $model = json_decode($jsonModel);

        $json = file_get_contents($https . "/seeds/" . $_GET['seed'] . ".json");
        $seed = json_decode($json);
        $api->seed($model, $seed);

        $con->commit();

        $response = array(
            'message' => 'Seeding Successfully.',
            'data' => []
        );
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($response);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
