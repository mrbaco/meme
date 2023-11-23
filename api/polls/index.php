<?php

define('ROOT', dirname(__FILE__));

$response = [];

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        if (!isset($_GET['poll_id']) || !is_numeric($_GET['poll_id'])) {
            header('HTTP/1.1 400 Bad Request');

            $response = [
                'error' => 'Param "poll_id" is required!'
            ];

            break;
        }

        $polls = json_decode(file_get_contents(ROOT . '/polls.json'));

        foreach ($polls as $k => $poll) {
            if ($poll->id == $_GET['poll_id']) {
                header('HTTP/1.1 200 OK');

                $response = $poll;

                break 2;
            }
        }
        
        header('HTTP/1.1 404 Not Found');

        $response = [
            'error' => 'Poll doesn\'t exist!'
        ];

        break;
    case 'POST':
        $request = json_decode(file_get_contents("php://input"));

        if (!isset($request->poll_id) || !is_numeric($request->poll_id)) {
            header('HTTP/1.1 400 Bad Request');

            $response = [
                'error' => 'Param "poll_id" is required!'
            ];

            break;
        }

        if (!isset($request->option_id) || !is_numeric($request->option_id)) {
            header('HTTP/1.1 400 Bad Request');

            $response = [
                'error' => 'Param "option_id" is required!'
            ];

            break;
        }

        $polls = json_decode(file_get_contents(ROOT . '/polls.json'));

        for ($poll = 0; $poll < sizeof($polls); $poll++) {
            if ($polls[$poll]->id == $request->poll_id) {
                for ($option = 0; $option < sizeof($polls[$poll]->options); $option++) {
                    if ($polls[$poll]->options[$option]->id == $request->option_id) {
                        header('HTTP/1.1 202 Accepted');

                        $polls[$poll]->options[$option]->results = $polls[$poll]->options[$option]->results + 1;
			
                        file_put_contents(ROOT . '/polls.json', json_encode($polls));

                        break 3;
                    }
                }
            }
        }

        header('HTTP/1.1 404 Not Found');

        $response = [
            'error' => 'Poll or option don\'t exist!'
        ];

        break;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

echo json_encode($response);

?>
