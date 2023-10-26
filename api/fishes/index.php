<?php

define('ROOT', dirname(__FILE__));

$control_line = [ // шаг 1.39
	'qwe', // 80px
	'button',
	'food',
	'rty',
	'qwe_sized',
	'rty_sized',
	245,
	50.45,
	290.45,
	'привет',
	'привет, море!',
	'я маленький апельсин',
	0,
	1,
	2,
	3,
	4,
	5,
	6,
	7,
	8,
	9,
	10,
	11,
	12,
	13,
	14,
	15,
	16,
	17,
	18,
	19,
	20,
	21,
	22,
	23,
	24,
	25,
	26,
	27,
	28,
	29,
	30,
	31,
	32,
	33,
	34,
	35,
	36,
	37,
	38,
	39,
	40,
	41,
	42,
	43,
	44,
	45,
	46,
	47,
	48,
	49,
	50,
	51,
	52,
	53,
	54,
	55,
	56,
	57,
	58,
	59,
	60,
	61,
	62,
	63,
	64,
	65,
	66,
	67,
	68,
	69,
	70,
	71,
	72,
	73,
	74,
	75,
	76,
	77,
	78,
	79,
	80,
	81,
	82,
	83,
	84,
	85,
	86,
	87,
	88,
	89,
	90,
	91,
	92,
	93,
	94,
	95,
	96,
	97,
	98,
	99,
	0,
	2,
	4,
	6,
	8,
	10,
	12,
	14,
	16,
	18,
	20,
	22,
	24,
	26,
	28,
	30,
	32,
	34,
	36,
	38,
	40,
	42,
	44,
	46,
	48,
	50,
	52,
	54,
	56,
	58,
	60,
	62,
	64,
	66,
	68,
	70,
	72,
	74,
	76,
	78,
	80,
	82,
	84,
	86,
	88,
	90,
	92,
	94,
	96,
	98,
	100,
	101,
	102,
	103,
	104,
	105,
	106,
	107,
	108,
	109,
	110,
	111,
	112,
	113,
	114,
	115,
	116,
	117,
	118,
	119,
	120,
	121,
	122,
	123,
	124,
	125,
	126,
	127,
	128,
	129,
	130,
	131,
	132,
	133,
	134,
	135,
	136,
	137,
	138,
	139,
	140,
	141,
	142,
	143,
	144,
	145,
	146,
	147,
	148,
	149,
	150,
	151,
	152,
	153,
	154,
	155,
	156,
	157,
	158,
	159,
	160,
	161,
	162,
	163,
	164,
	165,
	166,
	167,
	168,
	169,
	170,
	171,
	172,
	173,
	174,
	175,
	176,
	177,
	178,
	179,
	180,
	181,
	182,
	183,
	184,
	185,
	186,
	187,
	188,
	189,
	190,
	191,
	192,
	193,
	194,
	195,
	196,
	197,
	198,
	199,
	'я поле ввода',
	'я окно',
	'мышь' // 450px
];

$response = [];

switch (strtolower($_SERVER['REQUEST_METHOD'])) {
    case 'post':
        $request = json_decode(file_get_contents("php://input"));

        if (isset($request->delete) && $request->delete == true) {
            $files = glob(ROOT . '/data/*');
            foreach($files as $filename) {
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
    
            header('HTTP/1.1 204 No Content');
    
            break;
        }

        $id = time() . '_' . rand(11111, 99999);

        $result = file_put_contents(
            ROOT . '/data/' . $id . '.txt',
            json_encode([
                'id' => $id,
                'message' => '',
                'image' => rand(1, 18),
                'level' => 1
            ], JSON_FORCE_OBJECT)
        );

        if (!$result) {
            header('HTTP/1.1 503 Service Unavailable');
            $response = [
                'error' => 'Error while creating fish!'
            ];

            break;
        }

        $response = [
            'id' => $id
        ];

        header('HTTP/1.1 201 Created');

        break;

    case 'get':
        header('HTTP/1.1 200 OK');

        $files = glob(ROOT . '/data/*');

        natsort($files);

        foreach($files as $filename) {
            if (is_file($filename)) {
                $response[] = json_decode(file_get_contents($filename));
            }
        }

        break;

    case 'put':
        $request = json_decode(file_get_contents("php://input"));

        if (!isset($request->message)) {
            for ($i = 0; $i < sizeof($request->food); $i++) {
                if ($request->food[$i] != $control_line[$i]) {
                    header('HTTP/1.1 400 Bad Request');

                    $response = [
                        'result' => 'belch'
                    ];

                    break;
                }
            }
        }

        if (!file_exists(ROOT . '/data/' . $request->id . '.txt')) {
            header('HTTP/1.1 400 Bad Request');

            $response = [
                'result' => 'belch'
            ];

            break;
        }

        $fish = json_decode(file_get_contents(ROOT . '/data/' . $request->id . '.txt'));

        if (isset($request->message)) {
            $fish->message = htmlspecialchars($request->message);
        }

        if (isset($request->food)) {
            if ($fish->level == sizeof($request->food)) {
                header('HTTP/1.1 204 No Content');
            } else {
                header('HTTP/1.1 200 OK');
                $fish->level = sizeof($request->food);
            }
        } else {
            header('HTTP/1.1 204 No Content');
            $response = '';
        }

        file_put_contents(ROOT . '/data/' . $request->id . '.txt', json_encode($fish));
        
        break;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

if ($response != '') echo json_encode($response);

?>