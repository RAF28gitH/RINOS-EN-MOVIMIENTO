<?php
require_once 'conexion.php';

// Definir la acción actual. Si no hay, por defecto es 'listar'
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';
$mensaje = "";

switch ($accion) {
    
    // --- CASO 1: GUARDAR (CREAR O ACTUALIZAR) ---
    case 'guardar':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id']; // Campo oculto
            $pregunta = $_POST['pregunta'];
            $respuesta = $_POST['respuesta'];
            $categoria = $_POST['categoria'];
            $orden = $_POST['orden'];

            if (!empty($id)) {
                // TIENE ID -> ES UNA ACTUALIZACIÓN (UPDATE)
                $sql = "UPDATE faqs SET pregunta=?, respuesta=?, categoria=?, orden=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssii", $pregunta, $respuesta, $categoria, $orden, $id);
                $msg_txt = "actualizada";
            } else {
                // NO TIENE ID -> ES UNO NUEVO (INSERT)
                $sql = "INSERT INTO faqs (pregunta, respuesta, categoria, orden) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $pregunta, $respuesta, $categoria, $orden);
                $msg_txt = "creada";
            }

            if ($stmt->execute()) {
                header("Location: gestion_faq.php?mensaje=exito_$msg_txt");
                exit();
            } else {
                $mensaje = "Error al guardar: " . $conn->error;
            }
        }
        break;

    // --- CASO 2: ELIMINAR ---
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM faqs WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                header("Location: gestion_faq.php?mensaje=eliminado");
                exit();
            }
        }
        break;
        
    // --- CASO 3: FORMULARIO (PARA CREAR O EDITAR) ---
    case 'formulario':
        // Inicializar variables vacías
        $reg = ['id' => '', 'pregunta' => '', 'respuesta' => '', 'categoria' => '', 'orden' => ''];
        $titulo_form = "Nueva Pregunta";
        
        // Si hay un ID en la URL, se buscan los datos para llenar el form (EDICIÓN)
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM faqs WHERE id = $id";
            $res = $conn->query($sql);
            $reg = $res->fetch_assoc();
            $titulo_form = "Editar Pregunta #" . $id;
        }
        break;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de FAQs - CBTis 217</title>
    <link rel="stylesheet" href="../frontend/css/faq.css">
    </head>
<body>
    <main class="admin-container">
        <?php if ($accion == 'formulario'): ?>
            <div class="form-wrapper">
                <h2><?php echo $titulo_form; ?></h2>
                <form action="gestion_faq.php?accion=guardar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">

                    <div class="form-group">
                        <label>Pregunta:</label>
                        <input type="text" name="pregunta" value="<?php echo $reg['pregunta']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Respuesta:</label>
                        <textarea name="respuesta" rows="5" required><?php echo $reg['respuesta']; ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group half">
                            <label>Categoría:</label>
                            <input type="text" name="categoria" value="<?php echo $reg['categoria']; ?>">
                        </div>
                        <div class="form-group half">
                            <label>Orden:</label>
                            <input type="number" name="orden" value="<?php echo $reg['orden']; ?>">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Guardar Cambios</button>
                    <a href="gestion_faq.php" class="cancel-btn">Cancelar</a>
                </form>
            </div>

        <?php else: ?>
            
            <div class="header-actions">
                <h1>Administrar Preguntas Frecuentes</h1>
                <a href="gestion_faq.php?accion=formulario" class="btn-new">+ Nueva Pregunta</a>
            </div>

            <?php if (isset($_GET['mensaje'])): ?>
                <div class="alert success">Acción realizada con éxito.</div>
            <?php endif; ?>

            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Pregunta</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM faqs ORDER BY orden ASC";
                    $resultado = $conn->query($sql);
                    
                    if ($resultado->num_rows > 0) {
                        while($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $fila['orden'] . "</td>";
                            echo "<td>" . $fila['pregunta'] . "</td>";
                            echo "<td><span class='badge'>" . $fila['categoria'] . "</span></td>";
                            echo "<td>";
                            // BOTÓN EDITAR: Manda llamar al formulario con el ID
                            echo "<a href='gestion_faq.php?accion=formulario&id=" . $fila['id'] . "' class='btn-action edit'>Editar</a> ";
                            // BOTÓN ELIMINAR: Pide confirmación JS y manda llamar accion eliminar
                            echo "<a href='gestion_faq.php?accion=eliminar&id=" . $fila['id'] . "' onclick='return confirm(\"¿Estás seguro de borrar esto?\")' class='btn-action delete'>Borrar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay preguntas registradas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php endif; ?>

    </main>

</body>
</html>