<?php
session_start();
header('Content-Type: application/json');
$response = [
    'rol' => isset($_SESSION['rol']) ? $_SESSION['rol'] : 'guest',
    'logged_in' => isset($_SESSION['logged_in']) ? true : false
];
echo json_encode($response);
?>