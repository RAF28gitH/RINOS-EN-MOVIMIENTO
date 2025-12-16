<?php

$conexion = new mysqli("localhost", "root", "", "motos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM preguntas_frecuentes";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Preguntas Frecuentes</h1>
            </section>
            <section>
                <img src="img/logo.png" alt="CBTis217">
            </section>
        </header>
        <nav class="navvv p">
            <ul class="nav nav-pills justify-content-end" id="menu-navegacion">
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
                    <a class="nav-link" href="accidentes.php">Accidentes Viales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="">Preguntas Frecuentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuario/login.php">Iniciar Sesión</a>
                </li>
            </ul>
        </nav>
        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">PREGUNTAS FRECUENTES (FAQ)</h2>
            </div>
            <section class="d-flex justify-content-center">
                <div class="container my-4 faq-info">
                    <div class="accordion" id="accordionFAQ">
                        <?php
                        if ($resultado->num_rows > 0) {
                            $contador = 0;
                            while($fila = $resultado->fetch_assoc()) {
                                $contador++;
                                $idHeading = "heading" . $fila['id'];
                                $idCollapse = "collapse" . $fila['id'];
                        ?>
                                <div class="accordion-item mb-3 shadow-sm border-0">
                                    <h2 class="accordion-header" id="<?php echo $idHeading; ?>">
                                        <button class="accordion-button collapsed fw-bold text" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $idCollapse; ?>" aria-expanded="false" aria-controls="<?php echo $idCollapse; ?>" style="background-color: #f8f9fa;">
                                            <?php echo $fila['pregunta']; ?>
                                        </button>
                                    </h2>
                                    <div id="<?php echo $idCollapse; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $idHeading; ?>" data-bs-parent="#accordionFAQ">
                                        <div class="accordion-body text">
                                            <?php echo $fila['respuesta']; ?>
                                            <div class="mt-2 text-end">
                                                <small class="text-muted">Categoría: <?php echo $fila['categoria']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        else {
                            echo "<p class='text-center'>No hay preguntas frecuentes registradas.</p>";
                        }
                        $conexion->close();
                        ?>
                    </div>
                </div>
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
<script src="js/script.js"></script>
</html>