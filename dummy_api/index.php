<?php
    header('Content-Type: application/json');
    $raw_input = file_get_contents('php://input');
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
        $input = json_decode($raw_input);
        if (gettype($input) == 'string') $input = json_decode($input);
    } else
        parse_str($raw_input, $input);
    echo json_encode([
            'success'   => true,
            'code'      => 200,
            'error'     => null,
            '_GET'      => $_GET,
            '_POST'     => $_POST,
            'input'     => $input
        ]);