<?php

define('ROOT', dirname(__FILE__));

$response = [];

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $request = json_decode(file_get_contents("php://input"));
    $json = [];

    if (file_exists(ROOT . '/publications.json')) {
        $json = json_decode(file_get_contents(ROOT . '/publications.json'));
    }
    
    $json[] = stripslashes(substr(trim($request->publication), 0, 256));

    file_put_contents(ROOT . '/publications.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    header('HTTP/1.1 201 Created');
}

if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {

    if (file_exists(ROOT . '/publications.json')) {
        $response = array_reverse(json_decode(file_get_contents(ROOT . '/publications.json')));
    }

    header('HTTP/1.1 200 OK');
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>