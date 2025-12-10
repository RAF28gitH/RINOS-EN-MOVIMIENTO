<?php

$conexion = new mysqli("localhost", "root", "", "motos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT id, fecha, lugar, tipo_vehiculo, descripcion 
        FROM accidentes 
        WHERE tipo_vehiculo = 'Motocicleta'";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista de Accidentes en Motocicleta</title>
    
</head>

<body>

    <h2>🚨 Accidentes en Motocicleta</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Lugar</th>
            <th>Descripcion</th>
            <th>Causa</th>
            <th>Lecionados</th>
            <th>Uso_casco</th>
            <th>Nivel_gravedad</th>
            <th>Evidencia</th>
        </tr>

        <?php
        
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila['id'] . "</td>";
                echo "<td>" . $fila['fecha'] . "</td>";
                echo "<td>" . $fila['lugar'] . "</td>";
                echo "<td>" . $fila['descripcion'] . "</td>";
                echo "<td>" . $fila['causa'] . "</td>";
                echo "<td>" . $fila['lecionados'] . "</td>";
                echo "<td>" . $fila['uso_casco'] . "</td>";
                echo "<td>" . $fila['nivel_gravedad'] . "</td>";
                echo "<td>" . $fila['evidencis'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center'>No hay registros</td></tr>";
        }

        $conexion->close();
        ?>
    </table>

</body>
</html>
