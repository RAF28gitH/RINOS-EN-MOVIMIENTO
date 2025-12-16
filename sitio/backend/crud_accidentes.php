<?php
session_start();
require_once '../frontend/usuario/configdatabase.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado.");
}

$accion = $_GET['accion'] ?? 'listar';
$mensaje = "";

// Verificar mensajes de éxito
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'ok':
            $mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Accidente guardado correctamente
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            break;
        case 'eliminado':
            $mensaje = "<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Accidente eliminado correctamente
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            break;
    }
}

switch ($accion) {
    case 'guardar':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            $fecha = $_POST['fecha'];
            $lugar = $_POST['lugar'];
            $descripcion = $_POST['descripcion'];
            $causa = $_POST['causa'];
            $lesionados = $_POST['lesionados'];
            $uso_casco = $_POST['uso_casco'];
            $nivel_gravedad = $_POST['nivel_gravedad'];
            $evidencia = $_POST['evidencia'];

            // Convertir uso_casco a booleano (1=Sí, 0=No)
            $uso_casco_bool = ($uso_casco === 'Sí') ? 1 : 0;

            try {
                if (!empty($id)) {
                    // Actualizar accidente existente
                    $sql = "UPDATE accidentes SET 
                            fecha = ?, 
                            lugar = ?, 
                            descripcion = ?, 
                            causa = ?, 
                            lesionados = ?, 
                            uso_casco = ?, 
                            nivel_gravedad = ?, 
                            evidencia = ? 
                            WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $fecha, $lugar, $descripcion, $causa,
                        $lesionados, $uso_casco_bool, $nivel_gravedad, $evidencia, $id
                    ]);
                } else {
                    // Insertar nuevo accidente
                    $sql = "INSERT INTO accidentes (fecha, lugar, descripcion, causa, lesionados, uso_casco, nivel_gravedad, evidencia) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $fecha, $lugar, $descripcion, $causa,
                        $lesionados, $uso_casco_bool, $nivel_gravedad, $evidencia
                    ]);
                }
                header("Location: crud_accidentes.php?mensaje=ok");
                exit();
            } catch (PDOException $e) {
                $mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error al guardar: " . $e->getMessage() . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("DELETE FROM accidentes WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                header("Location: crud_accidentes.php?mensaje=eliminado");
                exit();
            } catch (PDOException $e) {
                $mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error al eliminar: " . $e->getMessage() . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        }
        break;

    case 'formulario':
        // Datos por defecto para nuevo registro
        $reg = [
            'id' => '',
            'fecha' => date('Y-m-d'),
            'lugar' => '',
            'descripcion' => '',
            'causa' => '',
            'lesionados' => 0,
            'uso_casco' => 0,
            'nivel_gravedad' => 'Leve',
            'evidencia' => ''
        ];
        $titulo_form = "Registrar Nuevo Accidente";
        
        // Si estamos editando, cargar los datos existentes
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM accidentes WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $reg = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($reg) {
                    $titulo_form = "Editar Accidente";
                    // Convertir booleano a texto para el formulario
                    $reg['uso_casco_text'] = ($reg['uso_casco'] == 1) ? 'Sí' : 'No';
                } else {
                    $mensaje = "<div class='alert alert-warning'>Accidente no encontrado</div>";
                }
            } catch (PDOException $e) {
                $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
        break;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Accidentes - Administración</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .table th {
            background-color: #ffc107;
            color: #212529;
        }
        .header {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            padding: 20px;
            color: white;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>
    <header class="header d-flex flex-row justify-content-between">
        <section>
            <h1 class="text">🚑 Gestión de Accidentes</h1>
            <p class="mb-0">Panel de Administración</p>
        </section>
        <section>
            <img src="../frontend/img/logo.png" alt="CBTis217" height="60">
        </section>
    </header>

    <div class="container mt-4">
        <!-- Mensajes de confirmación -->
        <?php echo $mensaje; ?>
        
        <!-- Botón volver -->
        <a href="../frontend/usuario/dashboard.php" class="btn btn-secondary mb-3">
            ← Volver al Dashboard
        </a>

        <?php if ($accion == 'formulario'): ?>
            <!-- FORMULARIO DE REGISTRO/EDICIÓN -->
            <div class="card p-4 shadow-lg">
                <h3 class="mb-4 text-center"><?php echo $titulo_form; ?></h3>
                
                <form action="crud_accidentes.php?accion=guardar" method="POST" onsubmit="return validarFormulario()">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reg['id']); ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha del Accidente:</label>
                                <input type="date" name="fecha" class="form-control" 
                                       value="<?php echo htmlspecialchars($reg['fecha']); ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Lugar:</label>
                                <input type="text" name="lugar" class="form-control" 
                                       value="<?php echo htmlspecialchars($reg['lugar']); ?>" 
                                       required
                                       placeholder="Ej: Av. Principal y Calle Secundaria">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Causa:</label>
                                <input type="text" name="causa" class="form-control" 
                                       value="<?php echo htmlspecialchars($reg['causa']); ?>"
                                       required
                                       placeholder="Ej: Exceso de velocidad, distracción...">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número de Lesionados:</label>
                                <input type="number" name="lesionados" class="form-control" 
                                       value="<?php echo htmlspecialchars($reg['lesionados']); ?>" 
                                       required min="0" max="50">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Uso de Casco:</label>
                                <select name="uso_casco" class="form-select" required>
                                    <option value="No" <?php echo (isset($reg['uso_casco_text']) && $reg['uso_casco_text'] == 'No') ? 'selected' : ''; ?>>No</option>
                                    <option value="Sí" <?php echo (isset($reg['uso_casco_text']) && $reg['uso_casco_text'] == 'Sí') ? 'selected' : ''; ?>>Sí</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nivel de Gravedad:</label>
                                <select name="nivel_gravedad" class="form-select" required>
                                    <option value="Leve" <?php echo ($reg['nivel_gravedad'] == 'Leve') ? 'selected' : ''; ?>>Leve</option>
                                    <option value="Moderado" <?php echo ($reg['nivel_gravedad'] == 'Moderado') ? 'selected' : ''; ?>>Moderado</option>
                                    <option value="Grave" <?php echo ($reg['nivel_gravedad'] == 'Grave') ? 'selected' : ''; ?>>Grave</option>
                                    <option value="Fatal" <?php echo ($reg['nivel_gravedad'] == 'Fatal') ? 'selected' : ''; ?>>Fatal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Evidencia (URL o descripción):</label>
                        <input type="text" name="evidencia" class="form-control" 
                               value="<?php echo htmlspecialchars($reg['evidencia']); ?>"
                               required
                               placeholder="Ej: https://ejemplo.com/imagen.jpg o 'Testigos presentes'">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción del Accidente:</label>
                        <textarea name="descripcion" class="form-control" rows="5" 
                                  placeholder="Describa los hechos del accidente..." 
                                  required><?php echo htmlspecialchars($reg['descripcion']); ?></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-warning btn-lg px-4">
                            💾 Guardar Accidente
                        </button>
                        <a href="crud_accidentes.php" class="btn btn-outline-secondary btn-lg px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <!-- LISTA DE ACCIDENTES -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">📋 Registro de Accidentes</h2>
                    <a href="crud_accidentes.php?accion=formulario" class="btn btn-dark btn-lg fw-bold">
                        + Nuevo Accidente
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-warning">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Lugar</th>
                                    <th>Causa</th>
                                    <th>Lesionados</th>
                                    <th>Uso Casco</th>
                                    <th>Gravedad</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT * FROM accidentes ORDER BY fecha DESC, id DESC");
                                    $total = 0;
                                    
                                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $total++;
                                        $uso_casco_text = ($fila['uso_casco'] == 1) ? 'Sí' : 'No';
                                        $uso_casco_color = ($fila['uso_casco'] == 1) ? 'success' : 'danger';
                                        
                                        echo "<tr>";
                                        echo "<td><strong>" . htmlspecialchars($fila['fecha']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($fila['lugar']) . "</td>";
                                        echo "<td>" . htmlspecialchars($fila['causa']) . "</td>";
                                        echo "<td><span class='badge bg-danger'>" . $fila['lesionados'] . "</span></td>";
                                        echo "<td><span class='badge bg-$uso_casco_color'>$uso_casco_text</span></td>";
                                        
                                        // Color según gravedad
                                        $color = '';
                                        switch ($fila['nivel_gravedad']) {
                                            case 'Leve': $color = 'bg-success'; break;
                                            case 'Moderado': $color = 'bg-warning'; break;
                                            case 'Grave': $color = 'bg-danger'; break;
                                            case 'Fatal': $color = 'bg-dark'; break;
                                        }
                                        echo "<td><span class='badge $color'>" . $fila['nivel_gravedad'] . "</span></td>";
                                        
                                        echo "<td class='text-center'>";
                                        echo "<a href='crud_accidentes.php?accion=formulario&id={$fila['id']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>";
                                        echo "<a href='crud_accidentes.php?accion=eliminar&id={$fila['id']}' 
                                              onclick='return confirm(\"¿Está seguro de eliminar este accidente?\\n\\nFecha: " . $fila['fecha'] . "\\nLugar: " . addslashes($fila['lugar']) . "\")' 
                                              class='btn btn-sm btn-danger'>🗑️ Eliminar</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    
                                    if ($total == 0) {
                                        echo "<tr><td colspan='7' class='text-center text-muted py-4'>No hay accidentes registrados</td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='7' class='text-center text-danger py-4'>Error al cargar los datos: " . $e->getMessage() . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (isset($total) && $total > 0): ?>
                        <div class="mt-3 text-end">
                            <span class="badge bg-secondary">Total: <?php echo $total; ?> accidentes</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="../frontend/js/bootstrap.bundle.min.js"></script>
    <script>
        function validarFormulario() {
            const fecha = document.querySelector('input[name="fecha"]').value;
            const lugar = document.querySelector('input[name="lugar"]').value.trim();
            const causa = document.querySelector('input[name="causa"]').value.trim();
            const lesionados = document.querySelector('input[name="lesionados"]').value;
            const descripcion = document.querySelector('textarea[name="descripcion"]').value.trim();
            const evidencia = document.querySelector('input[name="evidencia"]').value.trim();
            
            if (fecha === '') {
                alert('Por favor seleccione una fecha');
                return false;
            }
            
            if (lugar === '') {
                alert('Por favor ingrese el lugar del accidente');
                return false;
            }
            
            if (causa === '') {
                alert('Por favor ingrese la causa del accidente');
                return false;
            }
            
            if (lesionados === '' || lesionados < 0) {
                alert('Por favor ingrese un número válido de lesionados (0 o más)');
                return false;
            }
            
            if (descripcion === '') {
                alert('Por favor ingrese una descripción del accidente');
                return false;
            }
            
            if (evidencia === '') {
                alert('Por favor ingrese la evidencia o URL');
                return false;
            }
            
            return true;
        }
        
        // Configurar fecha máxima como hoy
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.querySelector('input[name="fecha"]');
            if (fechaInput) {
                const hoy = new Date().toISOString().split('T')[0];
                fechaInput.max = hoy;
            }
        });
    </script>
</body>
</html>