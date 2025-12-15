<?php
$host = "localhost";
$usuario = "root";     
$password = "";        
$base_datos = "dana";

// Crear la conexión
$conn = new mysqli($host, $usuario, $password, $base_datos);

// Verificar si hubo error en la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 para evitar problemas con tildes y ñ
$conn->set_charset("utf8mb4");
?>