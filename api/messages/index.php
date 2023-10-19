<?php

define('ROOT', dirname(__FILE__));

$response = [];

switch (strtolower($_SERVER['REQUEST_METHOD'])) {
    case 'post':
        $request = json_decode(file_get_contents("php://input"));

        if (isset($request->delete) && $request->delete == true) {
            $files = glob(ROOT . '/list/*');
            foreach($files as $filename) {
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
    
            header('HTTP/1.1 204 No Content');
    
            break;
        }

        if (
            (!isset($request->name) || empty($request->name)) || 
            (!isset($request->message) || empty($request->message))
        ) {
            header('HTTP/1.1 400 Bad Request');
            $response = [
                'error' => 'Parameters "name", "message" are required!'
            ];

            break;
        }

        $result = file_put_contents(
            ROOT . '/list/' . time() . '_' . rand(11111, 99999) . '.txt',
            sprintf('<b>%s:</b> %s', htmlspecialchars($request->name), addslashes($request->message))
        );

        if (!$result) {
            header('HTTP/1.1 503 Service Unavailable');
            $response = [
                'error' => 'Error while creating message!'
            ];

            break;
        }

        header('HTTP/1.1 201 Created');

        break;

    case 'get':
        header('HTTP/1.1 200 OK');

        $files = glob(ROOT . '/list/*');
        $files = array_slice($files, -30);

        natsort($files);

        foreach($files as $filename) {
            if (is_file($filename)) {
                $content = file_get_contents($filename);
                if (preg_match_all('/<b>(.*?):<\/b> (.*)/', $content, $matches)) {
                    $response[] = [
                        'id' => basename($filename, '.txt'),
                        'name' => $matches[1][0],
                        'message' => $matches[2][0]
                    ];
                }
            }
        }

        break;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

echo json_encode($response);

?>