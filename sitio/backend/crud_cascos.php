<?php
session_start();
require_once '../frontend/usuario/configdatabase.php';

if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){ 
    die("Acceso denegado."); 
}
$accion = $_GET['accion'] ?? 'listar';
$mensaje = "";
if(isset($_GET['mensaje'])){
    switch ($_GET['mensaje']){
        case 'ok':
            $mensaje = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Casco guardado correctamente
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            break;
        case 'eliminado':
            $mensaje = "<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Casco eliminado correctamente
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            break;
    }
}

switch ($accion){
    case 'guardar':
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST['id'] ?? '';
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $tipo = $_POST['tipo'];
            $certificacion = $_POST['certificacion'];
            $descripcion = $_POST['descripcion'];
            $precio_aprox = $_POST['precio_aprox'];
            $imagen_data = $_POST['imagen_actual'] ?? '';
            if(isset($_FILES['archivo_imagen']) && $_FILES['archivo_imagen']['error'] === UPLOAD_ERR_OK){
                $imagen_tmp = $_FILES['archivo_imagen']['tmp_name'];
                $imagen_contenido = file_get_contents($imagen_tmp);
                $imagen_data = base64_encode($imagen_contenido);
            } elseif(empty($imagen_data) && empty($id)){
                $default_image_path = '../frontend/img/default-helmet.png';
                if(file_exists($default_image_path)){
                    $imagen_contenido = file_get_contents($default_image_path);
                    $imagen_data = base64_encode($imagen_contenido);
                }
            }

            try{
                if(!empty($id)){
                    $sql = "UPDATE cascos SET 
                            Marca = ?, 
                            Modelo = ?, 
                            Tipo = ?, 
                            Certificacion = ?, 
                            Descripcion = ?, 
                            Precio_aprox = ?, 
                            Imagen = ? 
                            WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $marca, $modelo, $tipo, $certificacion, 
                        $descripcion, $precio_aprox, $imagen_data, $id
                    ]);
                } else{
                    $sql = "INSERT INTO cascos (Marca, Modelo, Tipo, Certificacion, Descripcion, Precio_aprox, Imagen, Fecha_registro) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $marca, $modelo, $tipo, $certificacion, 
                        $descripcion, $precio_aprox, $imagen_data
                    ]);
                }
                header("Location: crud_cascos.php?mensaje=ok");
                exit();
            } catch (PDOException $e){
                $mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error al guardar: " . $e->getMessage() . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        }
        break;

    case 'eliminar':
        if(isset($_GET['id'])){
            try{
                $stmt = $pdo->prepare("DELETE FROM cascos WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                header("Location: crud_cascos.php?mensaje=eliminado");
                exit();
            } catch (PDOException $e){
                $mensaje = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error al eliminar: " . $e->getMessage() . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
            }
        }
        break;

    case 'formulario':
        $reg = [
            'id' => '',
            'Marca' => '',
            'Modelo' => '',
            'Tipo' => '',
            'Certificacion' => '',
            'Descripcion' => '',
            'Precio_aprox' => '',
            'Imagen' => ''
        ];
        $titulo_form = "Registrar Nuevo Casco";
        if(isset($_GET['id'])){
            try{
                $stmt = $pdo->prepare("SELECT * FROM cascos WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $reg = $stmt->fetch(PDO::FETCH_ASSOC);
                if($reg){
                    $titulo_form = "Editar Casco";
                } else{
                    $mensaje = "<div class='alert alert-warning'>Casco no encontrado</div>";
                }
            } catch (PDOException $e){
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
    <title>Gestión de Cascos - Administración</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/style.css">
    <style>
        body{
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .header{
            background: linear-gradient(135deg, #0dcaf0 0%, #0da2c4 100%);
            color: white;
            padding: 20px;
            border-radius: 0 0 10px 10px;
        }
        .btn-info{
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
        .btn-info:hover{
            background-color: #0da2c4;
            border-color: #0da2c4;
        }
        .table th{
            background-color: #0dcaf0;
            color: white;
        }
        .card{
            border: 2px solid #0dcaf0;
        }
        .img-preview{
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header d-flex flex-row justify-content-between">
        <section>
            <h1 class="text">🛡️ Gestión de Cascos</h1>
            <p class="mb-0">Panel de Administración</p>
        </section>
        <section>
            <img src="../frontend/img/logo.png" alt="CBTis217" height="60">
        </section>
    </header>

    <div class="container mt-4">
        <?php echo $mensaje; ?>
        <a href="../frontend/usuario/dashboard.php" class="btn btn-secondary mb-3">
            ← Volver al Dashboard
        </a>

        <?php if($accion == 'formulario'): ?>
            <div class="card p-4 shadow-lg">
                <h3 class="mb-4 text-center text-info"><?php echo $titulo_form; ?></h3>

                <form action="crud_cascos.php?accion=guardar" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reg['id']); ?>">
                    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($reg['Imagen']); ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Marca:</label>
                            <input type="text" name="marca" class="form-control" 
                                   value="<?php echo htmlspecialchars($reg['Marca']); ?>" 
                                   required
                                   placeholder="Ej: Shoei, AGV, Bell">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Modelo:</label>
                            <input type="text" name="modelo" class="form-control" 
                                   value="<?php echo htmlspecialchars($reg['Modelo']); ?>" 
                                   required
                                   placeholder="Ej: X-Fourteen, C5, Race Star">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tipo:</label>
                            <select name="tipo" class="form-select" required>
                                <option value="Integral" <?php echo ($reg['Tipo'] == 'Integral') ? 'selected' : ''; ?>>Integral</option>
                                <option value="Abatible" <?php echo ($reg['Tipo'] == 'Abatible') ? 'selected' : ''; ?>>Abatible (Modular)</option>
                                <option value="Jet" <?php echo ($reg['Tipo'] == 'Jet') ? 'selected' : ''; ?>>Jet (Abierto)</option>
                                <option value="Cross" <?php echo ($reg['Tipo'] == 'Cross') ? 'selected' : ''; ?>>Cross/Off-road</option>
                                <option value="Doble" <?php echo ($reg['Tipo'] == 'Doble') ? 'selected' : ''; ?>>Doble Visor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Certificación:</label>
                            <select name="certificacion" class="form-select" required>
                                <option value="DOT" <?php echo ($reg['Certificacion'] == 'DOT') ? 'selected' : ''; ?>>DOT (EE.UU.)</option>
                                <option value="ECE" <?php echo ($reg['Certificacion'] == 'ECE') ? 'selected' : ''; ?>>ECE 22.05 (Europa)</option>
                                <option value="ECE 22.06" <?php echo ($reg['Certificacion'] == 'ECE 22.06') ? 'selected' : ''; ?>>ECE 22.06 (Nueva)</option>
                                <option value="Snell" <?php echo ($reg['Certificacion'] == 'Snell') ? 'selected' : ''; ?>>Snell</option>
                                <option value="SHARP" <?php echo ($reg['Certificacion'] == 'SHARP') ? 'selected' : ''; ?>>SHARP</option>
                                <option value="NOM" <?php echo ($reg['Certificacion'] == 'NOM') ? 'selected' : ''; ?>>NOM (México)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Precio Aproximado (MXN):</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" name="precio_aprox" class="form-control" 
                                   value="<?php echo htmlspecialchars($reg['Precio_aprox']); ?>" 
                                   required
                                   placeholder="Ej: 2,500.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Imagen del Casco:</label>
                        <input type="file" name="archivo_imagen" class="form-control" 
                               accept="image/*" 
                               onchange="previewImage(this)">
                        <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                        <div id="imagePreview" class="mt-3">
                            <?php if(!empty($reg['Imagen'])): ?>
                                <p class="mb-2">Imagen actual:</p>
                                <img src="data:image/jpeg;base64,<?php echo $reg['Imagen']; ?>" 
                                     class="img-preview" 
                                     alt="Imagen actual">
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción:</label>
                        <textarea name="descripcion" class="form-control" rows="4" 
                                  placeholder="Describa las características del casco..." 
                                  required><?php echo htmlspecialchars($reg['Descripcion']); ?></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-info btn-lg px-4 text-white">
                            💾 Guardar Casco
                        </button>
                        <a href="crud_cascos.php" class="btn btn-outline-secondary btn-lg px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">🛡️ Catálogo de Cascos</h2>
                    <a href="crud_cascos.php?accion=formulario" class="btn btn-light btn-lg fw-bold">
                        + Nuevo Casco
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Tipo</th>
                                    <th>Certificación</th>
                                    <th>Precio</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try{
                                    $stmt = $pdo->query("SELECT * FROM cascos ORDER BY Marca, Modelo");
                                    $total = 0;
                                    
                                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        $total++;
                                        $imagen_src = !empty($fila['Imagen']) 
                                            ? "data:image/jpeg;base64," . $fila['Imagen'] 
                                            : '../frontend/img/default-helmet.png';
                                        
                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<img src='$imagen_src' 
                                                class='img-thumbnail' 
                                                style='width: 60px; height: 60px; object-fit: cover;'
                                                alt='" . htmlspecialchars($fila['Marca'] . ' ' . $fila['Modelo']) . "'>";
                                        echo "</td>";
                                        echo "<td><strong>" . htmlspecialchars($fila['Marca']) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars($fila['Modelo']) . "</td>";
                                        echo "<td><span class='badge bg-primary'>" . $fila['Tipo'] . "</span></td>";
                                        echo "<td><span class='badge bg-warning text-dark'>" . $fila['Certificacion'] . "</span></td>";
                                        echo "<td><span class='badge bg-success'>$" . $fila['Precio_aprox'] . "</span></td>";
                                        echo "<td class='text-center'>";
                                        echo "<a href='crud_cascos.php?accion=formulario&id={$fila['id']}' class='btn btn-sm btn-primary me-1'>✏️ Editar</a>";
                                        echo "<a href='crud_cascos.php?accion=eliminar&id={$fila['id']}' 
                                              onclick='return confirm(\"¿Está seguro de eliminar este casco?\\n\\nMarca: " . addslashes($fila['Marca']) . "\\nModelo: " . addslashes($fila['Modelo']) . "\")' 
                                              class='btn btn-sm btn-danger'>🗑️ Eliminar</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    
                                    if($total == 0){
                                        echo "<tr><td colspan='7' class='text-center text-muted py-4'>No hay cascos registrados</td></tr>";
                                    }
                                } catch (PDOException $e){
                                    echo "<tr><td colspan='7' class='text-center text-danger py-4'>Error al cargar los datos: " . $e->getMessage() . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if(isset($total) && $total > 0): ?>
                        <div class="mt-3 text-end">
                            <span class="badge bg-secondary">Total: <?php echo $total; ?> cascos</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="../frontend/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input){
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if(input.files && input.files[0]){
                const reader = new FileReader();
                
                reader.onload = function(e){
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-preview';
                    img.alt = 'Vista previa';
                    
                    const p = document.createElement('p');
                    p.className = 'mb-2 fw-bold';
                    p.textContent = 'Vista previa:';
                    
                    preview.appendChild(p);
                    preview.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function validarFormulario(){
            const marca = document.querySelector('input[name="marca"]').value.trim();
            const modelo = document.querySelector('input[name="modelo"]').value.trim();
            const precio = document.querySelector('input[name="precio_aprox"]').value.trim();
            const descripcion = document.querySelector('textarea[name="descripcion"]').value.trim();
            
            if(marca === ''){
                alert('Por favor ingrese la marca del casco');
                return false;
            }
            
            if(modelo === ''){
                alert('Por favor ingrese el modelo del casco');
                return false;
            }
            
            if(precio === ''){
                alert('Por favor ingrese el precio aproximado');
                return false;
            }
            
            if(descripcion === ''){
                alert('Por favor ingrese una descripción del casco');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>