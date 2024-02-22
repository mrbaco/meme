<?php

header('Access-Control-Allow-Origin: *');

$board = '000-000-000-0';

if (file_exists('board.txt')) {
    $board = file_get_contents('board.txt');
}

if (isset($_GET['get_board'])) {
    echo $board;
}

if (isset($_GET['set_board'])) {
    if (substr($board, -1) != substr($_GET['set_board'], -1)) {
        file_put_contents('board.txt', $_GET['set_board']);
    }
}
