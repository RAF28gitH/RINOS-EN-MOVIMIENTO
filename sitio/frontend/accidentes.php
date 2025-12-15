<?php

$conexion = new mysqli("localhost", "root", "", "motos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM accidentes";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accidentes en Motocicleta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Accidentes</h1>
            </section>
            <section>
                <img src="img/logo.png" alt="CBTis217">
            </section>
        </header>
        <nav class="navvv p">
            <ul class="nav nav-pills justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="inicio.html">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="practicas-seguras.html">Prácticas Seguras</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cascos.php">Tipos De Casco</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto-compromiso.html">Contacto / Compromiso</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="normativa-reglamento.html">Normativa y Reglamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Accidentes Viales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="FAQ.php">Preguntas Frecuentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuario/login.php">Iniciar Sesión</a>
                </li>
            </ul>
        </nav>
        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">ACCIDENTES EN MOTOCICLETA</h2>
            </div>
            <section class="d-flex flex-column justify-content-center">
                <h4 class="text">
                    Los accidentes en motocicleta son una causa importante de lesiones y muertes viales,
                    con mayor riesgo para las motocicletas por falta de protección.
                    para prevenirlos es importante: usar protección, cumplir normas y darle servicio a la motocicleta.
                </h4>
                
                <table class="tabla-motos">
                    <tr class="text">
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Descripcion</th>
                        <th>Causa</th>
                        <th>Lesionados</th>
                        <th>Uso_casco</th>
                        <th>Nivel_gravedad</th>
                        <th>Evidencia</th>
                    </tr>

                    <?php
                    
                    if($resultado->num_rows > 0){
                        while($fila = $resultado->fetch_assoc()){
                            echo "<tr>";
                            echo "<td>" . $fila['fecha'] . "</td>";
                            echo "<td>" . $fila['lugar'] . "</td>";
                            echo "<td>" . $fila['descripcion'] . "</td>";
                            echo "<td>" . $fila['causa'] . "</td>";
                            echo "<td>" . $fila['lesionados'] . "</td>";
                            echo "<td>" . $fila['uso_casco'] . "</td>";
                            echo "<td>" . $fila['nivel_gravedad'] . "</td>";
                            echo "<td>" . $fila['evidencia'] . "</td>";
                            echo "</tr>";
                        }
                    }
                    else{
                        echo "<tr><td class='text-center' colspan='8'>No hay registros</td></tr>";
                    }

                    $conexion->close();
                    ?>
                </table>
            </section>
        </div>
        <footer class="footer p">
            <h4 class="text">Página de CBTis217</h4>
            <div class="borde-accordion">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <strong class="text">Integrantes</strong>
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body text">
                                <ul>
                                    <li><strong>JUAN RAFAEL GONZÁLEZ DÍAZ</strong></li>
                                    <li><strong>DAVID ALMANZA LÓPEZ</strong></li>
                                    <li><strong>DANA CAMILA NIETO OROZCO</strong></li>
                                    <li><strong>MICHELLE ROMERO ÁVILA</strong></li>
                                    <li><strong>JOCELIN ROCHA GARNICA</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
