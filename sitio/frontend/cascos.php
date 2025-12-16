<?php
require_once 'usuario/configdatabase.php';

try {
    $stmt = $pdo->query("SELECT * FROM cascos ORDER BY Fecha_registro DESC");
    $cascos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar los cascos. Por favor intente más tarde.";
    $cascos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos De Cascos - Seguridad Vial</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .principal {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .recuadro-page {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .tabla-motos {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        .tabla-motos th {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .tabla-motos td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .tabla-motos tr:hover {
            background-color: #f8f9fa;
        }
        .badge-gravedad {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge-leve { background-color: #28a745; color: white; }
        .badge-moderado { background-color: #ffc107; color: #212529; }
        .badge-grave { background-color: #fd7e14; color: white; }
        .badge-fatal { background-color: #dc3545; color: white; }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #dc3545;
        }
        .stats-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Cascos</h1>
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
        
        <main>
            <div class="recuadro-page d-flex justify-content-center align-items-center py-4">
                <h2 class="mb-0">🛡️ CASCOS DE PROTECCIÓN</h2>
            </div>
            
            <section class="d-flex justify-content-center py-4">
                <div class="container my-4 cascos-info">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if (empty($cascos)): ?>
                        <div class="alert alert-info text-center py-5">
                            <h4>📭 No hay cascos registrados</h4>
                            <p class="mb-0">Actualmente no hay información disponible sobre cascos.</p>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php foreach ($cascos as $casco): ?>
                                <?php
                                $imagen_src = '';
                                if (!empty($casco['Imagen'])) {
                                    if (strpos($casco['Imagen'], 'data:image') === 0 || 
                                        preg_match('/^[a-zA-Z0-9\/+]+={0,2}$/', $casco['Imagen'])) {
                                        $imagen_src = "data:image/jpeg;base64," . $casco['Imagen'];
                                    }
                                    else {
                                        $imagen_src = htmlspecialchars($casco['Imagen']);
                                        if (!file_exists($imagen_src) && !filter_var($imagen_src, FILTER_VALIDATE_URL)) {
                                            $imagen_src = "img/default-helmet.png";
                                        }
                                    }
                                }
                                else {
                                    $imagen_src = "img/default-helmet.png";
                                }
                                ?>
                                
                                <div class="col">
                                    <div class="card h-100 p-3 shadow-sm">
                                        <img src="<?php echo $imagen_src; ?>" 
                                             class="card-img-top" 
                                             alt="Casco <?php echo htmlspecialchars($casco['Marca'] . ' ' . $casco['Modelo']); ?>"
                                             onerror="this.src='img/default-helmet.png'">
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h3 class="card-title text-center mb-3">
                                                <?php echo htmlspecialchars($casco['Marca'] . ' ' . $casco['Modelo']); ?>
                                            </h3>
                                            
                                            <div class="text-center mb-3">
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($casco['Tipo']); ?></span>
                                                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($casco['Certificacion']); ?></span>
                                            </div>

                                            <p class="card-text" style="text-align: justify;">
                                                <?php echo htmlspecialchars($casco['Descripcion']); ?>
                                            </p>
                                            
                                            <div class="mt-auto pt-3 text-center border-top">
                                                <strong class="price-tag">$<?php echo htmlspecialchars($casco['Precio_aprox']); ?> MXN</strong>
                                                <br>
                                                <small class="text-muted">Registrado: <?php echo date('d/m/Y', strtotime($casco['Fecha_registro'])); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <div class="alert alert-info">
                                <h5>💡 Importancia del Casco</h5>
                                <p class="mb-0">
                                    El uso correcto del casco reduce en un 40% el riesgo de muerte 
                                    y en un 70% el riesgo de lesiones graves en accidentes de moto.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
        
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {            
            const cards = document.querySelectorAll('.cascos-info .card');
            cards.forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.classList.add('hover');
                });
                card.addEventListener('touchend', function() {
                    this.classList.remove('hover');
                });
            });
        });
    </script>
</body>
<script src="js/script.js"></script>
</html>