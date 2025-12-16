<?php
require_once 'usuario/configdatabase.php';

try{
    $stmt = $pdo->query("SELECT * FROM accidentes ORDER BY fecha DESC, id DESC");
    $accidentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    $error = "Error al cargar los accidentes. Por favor intente más tarde.";
    $accidentes = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accidentes en Motocicleta - Información Vial</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .principal{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .recuadro-page{
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .tabla-motos{
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        .tabla-motos th{
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .tabla-motos td{
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .tabla-motos tr:hover{
            background-color: #f8f9fa;
        }
        .badge-gravedad{
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge-leve{ background-color: #28a745; color: white; }
        .badge-moderado{ background-color: #ffc107; color: #212529; }
        .badge-grave{ background-color: #fd7e14; color: white; }
        .badge-fatal{ background-color: #dc3545; color: white; }
        .info-box{
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #dc3545;
        }
        .stats-box{
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="principal">
       <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Accidentes</h1>
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

        <main class="container py-4">
            <div class="recuadro-page">
                <h2 class="mb-3">🚨 ACCIDENTES EN MOTOCICLETA</h2>
                <p class="lead mb-0">Registro de incidentes viales para concientización y prevención</p>
            </div>
            <div class="info-box">
                <h4 class="mb-3">📊 Estadísticas y Prevención</h4>
                <p>Los accidentes en motocicleta representan una causa importante de lesiones y muertes viales. 
                Los motociclistas tienen un riesgo 27 veces mayor de morir en un accidente que los ocupantes de automóviles, 
                principalmente debido a la falta de protección.</p>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="stats-box text-center">
                            <h5>⚠️ Factores de Riesgo</h5>
                            <ul class="list-unstyled">
                                <li>• Exceso de velocidad</li>
                                <li>• No uso de casco</li>
                                <li>• Consumo de alcohol</li>
                                <li>• Malas condiciones climáticas</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-box text-center">
                            <h5>✅ Medidas Preventivas</h5>
                            <ul class="list-unstyled">
                                <li>• Usar casco certificado</li>
                                <li>• Respetar límites de velocidad</li>
                                <li>• Mantenimiento del vehículo</li>
                                <li>• Capacitación continua</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-box text-center">
                            <h5>📞 En caso de accidente</h5>
                            <ul class="list-unstyled">
                                <li>• Llamar al 911</li>
                                <li>• No mover al lesionado</li>
                                <li>• Señalizar la zona</li>
                                <li>• Conservar evidencia</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mb-4">📋 Registro de Accidentes Reportados</h3>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(empty($accidentes)): ?>
                <div class="alert alert-info text-center py-4">
                    <h4>📭 No hay accidentes registrados</h4>
                    <p class="mb-0">Actualmente no hay reportes de accidentes en el sistema.</p>
                </div>
            <?php else: ?>
                <?php
                $total_accidentes = count($accidentes);
                $total_lesionados = array_sum(array_column($accidentes, 'lesionados'));
                $con_casco = 0;
                foreach($accidentes as $acc){
                    if ($acc['uso_casco'] == 1) {
                        $con_casco++;
                    }
                }
                $sin_casco = $total_accidentes - $con_casco;
                ?>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-box text-center">
                            <h5>Total Accidentes</h5>
                            <h2 class="text-danger"><?php echo $total_accidentes; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center">
                            <h5>Total Lesionados</h5>
                            <h2 class="text-warning"><?php echo $total_lesionados; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center">
                            <h5>Con Casco</h5>
                            <h2 class="text-success"><?php echo $con_casco; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-box text-center">
                            <h5>Sin Casco</h5>
                            <h2 class="text-danger"><?php echo $sin_casco; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="tabla-motos">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Lugar</th>
                                <th>Descripción</th>
                                <th>Causa</th>
                                <th>Lesionados</th>
                                <th>Uso de Casco</th>
                                <th>Nivel de Gravedad</th>
                                <th>Evidencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($accidentes as $accidente): ?>
                                <?php
                                $uso_casco_text = ($accidente['uso_casco'] == 1) ? 'Sí' : 'No';
                                $uso_casco_color = ($accidente['uso_casco'] == 1) ? 'success' : 'danger';
                                
                                $clase_gravedad = '';
                                switch($accidente['nivel_gravedad']){
                                    case 'Leve': $clase_gravedad = 'badge-leve'; break;
                                    case 'Moderado': $clase_gravedad = 'badge-moderado'; break;
                                    case 'Grave': $clase_gravedad = 'badge-grave'; break;
                                    case 'Fatal': $clase_gravedad = 'badge-fatal'; break;
                                }
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($accidente['fecha']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($accidente['lugar']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($accidente['description'])); ?></td>
                                    <td><?php echo htmlspecialchars($accidente['causa']); ?></td>
                                    <td class="text-center">
                                        <?php if($accidente['lesionados'] > 0): ?>
                                            <span class="badge bg-danger"><?php echo $accidente['lesionados']; ?> personas</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $uso_casco_color; ?>">
                                            <?php echo $uso_casco_text; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-gravedad <?php echo $clase_gravedad; ?>">
                                            <?php echo $accidente['nivel_gravedad']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($accidente['evidencia']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning mt-4">
                    <h5>📌 Nota Importante:</h5>
                    <p class="mb-0">
                        Estos datos tienen fines informativos y de concientización. 
                        La mejor manera de prevenir accidentes es respetando las normas de tránsito, 
                        usando el equipo de protección adecuado y manteniendo la atención en la vía.
                    </p>
                </div>
            <?php endif; ?>
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
            const badges = document.querySelectorAll('.badge-gravedad');
            badges.forEach(badge => {
                badge.title = 'Nivel de gravedad: ' + badge.textContent;
            });
        });
    </script>
</body>
<script src="js/script.js"></script>
</html>