<?php
/* configdatabase.php */

// Credenciales de XAMPP por defecto
$host = 'localhost';
$dbname = 'Motos';  // El nombre exacto de tu base de datos
$username = 'root'; // Usuario por defecto de XAMPP
$password = '';     // En XAMPP la contraseña suele estar vacía

try {
    // Creamos la conexión usando PDO (la forma segura)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuramos para que nos avise si hay errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si falla, nos dice por qué
    die("¡Error fatal de conexión!: " . $e->getMessage());
}
?>