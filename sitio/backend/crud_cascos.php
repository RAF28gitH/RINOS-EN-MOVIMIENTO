<?php
session_start();
require_once '../frontend/usuario/configdatabase.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { die("Acceso denegado."); }

$accion = $_GET['accion'] ?? 'listar';

// CARPETA DONDE SE GUARDARÁN LAS FOTOS (Asegúrate que exista la carpeta 'img' fuera de 'backend')
$carpeta_destino = "../frontend/img/";

switch ($accion) {
    case 'guardar':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'];
            $tipo = $_POST['tipo'];
            $certificacion = $_POST['certificacion'];
            $descripcion = $_POST['descripcion'];
            
            // --- AQUÍ EMPIEZA LA MAGIA DE LA SUBIDA ---
            $imagen_url = $_POST['imagen_actual']; // Por defecto, dejamos la que ya estaba
            
            // Verificamos si el usuario subió una NUEVA imagen
            if (isset($_FILES['archivo_imagen']) && $_FILES['archivo_imagen']['error'] === UPLOAD_ERR_OK) {
                
                $nombre_archivo = basename($_FILES['archivo_imagen']['name']);
                $ruta_final = $carpeta_destino . $nombre_archivo;
                
                // PHP mueve el archivo desde tu compu a la carpeta del servidor
                if (move_uploaded_file($_FILES['archivo_imagen']['tmp_name'], $ruta_final)) {
                    // Si se movió bien, guardamos la ruta en la variable para la BD
                    // Guardamos "img/nombre.jpg" para que sirva desde el HTML
                    $imagen_url = "img/" . $nombre_archivo; 
                } else {
                    echo "Hubo un error al subir la imagen.";
                    exit;
                }
            }
            // ------------------------------------------

            if (!empty($id)) {
                $sql = "UPDATE cascos SET nombre=?, tipo=?, certificacion=?, descripcion=?, Imagen=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $tipo, $certificacion, $descripcion, $imagen_url, $id]);
            } else {
                // Si es nuevo y no subió imagen, ponemos una por defecto
                if(empty($imagen_url)) { $imagen_url = "img/default.jpg"; }
                
                $sql = "INSERT INTO cascos (nombre, tipo, certificacion, descripcion, Imagen, Precio_aprox, Fecha_registro) VALUES (?, ?, ?, ?, ?, '0', NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $tipo, $certificacion, $descripcion, $imagen_url]);
            }
            
            header("Location: crud_cascos.php"); exit();
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $pdo->prepare("DELETE FROM cascos WHERE id=?")->execute([$_GET['id']]);
            header("Location: crud_cascos.php"); exit();
        }
        break;

    case 'formulario':
        // Preparamos variables vacías
        $reg = ['id'=>'', 'Marca'=>'', 'Modelo'=>'', 'Tipo'=>'', 'Certificacion'=>'', 'Descripcion'=>'', 'Imagen'=>''];
        $titulo_form = "Nuevo Casco";
        
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM cascos WHERE id=?");
            $stmt->execute([$_GET['id']]);
            $reg = $stmt->fetch(PDO::FETCH_ASSOC);
            // Ajuste por si tus columnas se llaman diferente (revisando tu SQL)
            // Mapeamos lo que viene de la BD a variables simples
            $reg['nombre'] = $reg['Modelo'] ?? $reg['nombre'] ?? ''; 
            $reg['tipo'] = $reg['Tipo'] ?? '';
            $reg['certificacion'] = $reg['Certificacion'] ?? '';
            $reg['descripcion'] = $reg['Descripcion'] ?? '';
            $reg['imagen_url'] = $reg['Imagen'] ?? '';
            
            $titulo_form = "Editar Casco";
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cascos</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body class="bg-light">
    <header class="header d-flex flex-row justify-content-between">
        <section>
            <h1 class="text">Modificar Cascos</h1>
        </section>
        <section>
            <img src="../frontend/img/logo.png" alt="CBTis217">
        </section>
    </header>
    <div class="container mt-4">
        <a href="../frontend/usuario/dashboard.php" class="btn btn-secondary mb-3">⬅ Volver al Dashboard</a>
        
        <?php if ($accion == 'formulario'): ?>
            <div class="card p-4 shadow border-info">
                <h3 class="text-info"><?php echo $titulo_form; ?></h3>
                
                <form action="crud_cascos.php?accion=guardar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                    
                    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($reg['imagen_url'] ?? ''); ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre/Modelo:</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($reg['nombre'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tipo (Integral, Abatible...):</label>
                            <input type="text" name="tipo" class="form-control" value="<?php echo htmlspecialchars($reg['tipo'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Certificación:</label>
                        <input type="text" name="certificacion" class="form-control" value="<?php echo htmlspecialchars($reg['certificacion'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Imagen del Casco:</label>
                        <input type="file" name="archivo_imagen" class="form-control" accept="image/*">
                        <small class="text-muted">Si no seleccionas nada, se mantiene la imagen actual.</small>
                    </div>
                    
                    <?php if (!empty($reg['imagen_url'])): ?>
                        <div class="mb-3">
                            <p>Imagen Actual:</p>
                            <img src="../<?php echo $reg['imagen_url']; ?>" alt="Actual" style="max-height: 100px;">
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label>Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($reg['descripcion'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100">Guardar Casco</button>
                    <a href="crud_cascos.php" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🪖 Catálogo de Cascos</h2>
                <a href="crud_cascos.php?accion=formulario" class="btn btn-info text-white fw-bold">+ Nuevo Casco</a>
            </div>
            
            <div class="table-responsive bg-white p-3 shadow rounded">
                <table class="table table-hover align-middle">
                    <thead class="table-info">
                        <tr><th>Imagen</th><th>Modelo</th><th>Tipo</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM cascos");
                        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Ajuste de nombres de columnas según tu SQL
                            $nombre = $fila['Modelo'] ?? $fila['nombre'] ?? 'Sin nombre';
                            $tipo = $fila['Tipo'] ?? $fila['tipo'] ?? '';
                            // La imagen se guarda como 'img/archivo.jpg', le agregamos ../ para verla desde backend
                            $img = !empty($fila['Imagen']) ? "../" . $fila['Imagen'] : "../frontend/img/default.png";

                            echo "<tr>";
                            echo "<td><img src='$img' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px;'></td>";
                            echo "<td><strong>" . htmlspecialchars($nombre) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($tipo) . "</td>";
                            echo "<td>
                                <a href='crud_cascos.php?accion=formulario&id={$fila['id']}' class='btn btn-sm btn-primary'>Editar</a>
                                <a href='crud_cascos.php?accion=eliminar&id={$fila['id']}' onclick='return confirm(\"¿Borrar?\")' class='btn btn-sm btn-danger'>X</a>
                            </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>