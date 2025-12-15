<?php

$conexion = new mysqli("localhost", "root", "", "motos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM cascos";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos De Cascos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Tipos De Cascos</h1>
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
                    <a class="nav-link active" aria-current="page" href="">Tipos De Casco</a>
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
                    <a class="nav-link" href="FAQ.php">Preguntas Frecuentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuario/login.php">Iniciar Sesión</a>
                </li>
            </ul>
        </nav>
        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">CASCOS</h2>
            </div>
            <section class="d-flex justify-content-center">
                <div class="container my-4 cascos-info">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        
                        <?php
                        if ($resultado->num_rows > 0) {
                            while($fila = $resultado->fetch_assoc()) {
                        ?>
                            <div class="col">
                                <div class="card h-100 p shadow-sm">
                                    
                                    <?php if($fila['Imagen']): ?>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($fila['Imagen']); ?>" class="card-img-top" alt="Casco" style="height: 250px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="img/logo.png" class="card-img-top p-5" alt="Sin imagen" style="height: 250px; object-fit: contain;">
                                    <?php endif; ?>

                                    <div class="card-body d-flex flex-column">
                                        <h3 class="text card-title text-center">
                                            <?php echo $fila['Marca'] . " " . $fila['Modelo']; ?>
                                        </h3>
                                        
                                        <div class="text-center mb-3">
                                            <span class="badge bg-primary"><?php echo $fila['Tipo']; ?></span>
                                            <span class="badge bg-warning text-dark"><?php echo $fila['Certificacion']; ?></span>
                                        </div>

                                        <p class="card-text text" style="text-align: justify;">
                                            <?php echo $fila['Descripcion']; ?>
                                        </p>
                                        
                                        <div class="mt-auto pt-3 text-center border-top">
                                            <strong class="text fs-4">$<?php echo $fila['Precio_aprox']; ?></strong>
                                        </div>
                                    </div>
                                </div>
                                </div>
                        <?php
                            }
                        }
                        else{
                            echo "<h3 class='text-center w-100'>No hay cascos registrados aún.</h3>";
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
</html>