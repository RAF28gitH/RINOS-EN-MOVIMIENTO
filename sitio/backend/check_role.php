<?php
// check_role.php
session_start();
header('Content-Type: application/json');

// Verificamos si hay rol y lo devolvemos
$response = [
    'rol' => isset($_SESSION['rol']) ? $_SESSION['rol'] : 'guest',
    'logged_in' => isset($_SESSION['logged_in']) ? true : false
];

echo json_encode($response);
?>