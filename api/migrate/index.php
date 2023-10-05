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
        $json = file_get_contents($https . "/models/models.json");
        $allModel = json_decode($json);
        foreach ($allModel as $item) {
            $jsonModel = file_get_contents($https . "/models/" . $item . "-model.json");
            $model = json_decode($jsonModel);
            $api->createTable($model);
            $api->truncateTable($model);
        }
        $response = array(
            'message' => 'Model Migration Successfully.',
            'data' => []
        );

        $con->commit();
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
