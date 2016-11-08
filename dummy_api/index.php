<?php
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'));
    if (gettype($input) == 'string') $input = json_decode($input);
    echo json_encode([
            'success'   => true,
            'code'      => 200,
            'error'     => null,
            '_GET'      => $_GET,
            '_POST'     => $_POST,
            'input'     => $input
        ]);