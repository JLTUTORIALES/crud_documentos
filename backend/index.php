<?php

//CORS

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}

require_once 'vendor/autoload.php';

use backend\helpers\PayloadValidator;
use backend\helpers\RestErrorManager;
use backend\models\AuthModel;
use backend\services\AuthService;
use backend\services\DocumentProcessService;
use backend\services\DocumentService;
use backend\services\DocumentTypeService;

header('Content-Type: text/json');

$route = isset($_GET['route']) ? $_GET['route'] : '';

$endpoints = array(
    'auth' => new AuthService(),
    'process' => new DocumentProcessService(),
    'types' => new DocumentTypeService(),
    'documents' => new DocumentService()
);

$payload = json_decode(file_get_contents('php://input'), true);
$method = $_SERVER['REQUEST_METHOD'];

if (in_array($route, array_keys($endpoints))) {

    $validator = $endpoints[$route]->setValidator($method);
    $validator = PayloadValidator::validatePayload($payload, $validator);
    if ($validator !== true){
        echo RestErrorManager::error(400, $validator);
        return;
    }

    if ($route != 'auth'){
        AuthModel::validateToken();
    }

    http_response_code(200);
    
    $status = $endpoints[$route]->loadEndpoint($method, $payload);
    if (!$status){
        echo RestErrorManager::error(404, "$method /$route Not available!");
        return;
    }
} else {
    echo RestErrorManager::error(404, "$method /$route Not available!");
    return;
}
    
?>