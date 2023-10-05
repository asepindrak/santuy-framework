<?php
require_once '../database/connection.php';
require_once 'method.php';

use Generate\Api\Connection as Con;
use Generate\Api\GlobalApi as Api;

$con = new Con();
$api = new Api();

$request_method = $_SERVER["REQUEST_METHOD"];
if (empty($_GET["model"])) {
    header("HTTP/1.0 405 Method Not Allowed");
    return;
}

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            $api->getId($_GET["model"], $id);
        } else {
            $api->getAll($_GET["model"],);
        }
        break;
    case 'POST':
        $api->createData($_GET["model"], $_POST);
        break;
    case 'PUT':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            $api->updateData($_GET["model"], $id);
        }
        break;
    case 'DELETE':
        $id = intval($_GET["id"]);
        $api->deleteData($_GET["model"], $id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
        break;
}
