<?php
require_once 'configdatabase.php';

echo "Probando conexión...<br>";

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'usuarios' existe<br>";
        $stmt = $pdo->query("SELECT id, username, email, fname, rol, LENGTH(pass) as pass_length FROM usuarios");
        $usuarios = $stmt->fetchAll();
        
        if (count($usuarios) > 0) {
            echo "✅ Usuarios encontrados:<br>";
            foreach ($usuarios as $usuario) {
                echo "- {$usuario['username']} ({$usuario['rol']}) - Pass length: {$usuario['pass_length']}<br>";
            }
        } else {
            echo "❌ No hay usuarios en la tabla<br>";
        }
    } else {
        echo "❌ Tabla 'usuarios' NO existe<br>";
    }
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>