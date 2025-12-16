<?php
session_start();
require_once '../frontend/usuario/configdatabase.php'; // Tu conexión segura

// Seguridad: Solo admin entra aquí
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { die("Acceso denegado."); }

$accion = $_GET['accion'] ?? 'listar';
$mensaje = "";

switch ($accion) {
    case 'guardar':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha = $_POST['fecha'];
            $lugar = $_POST['lugar'];

            try {
                if (!empty($id)) {
                    $sql = "UPDATE accidentes SET titulo=?, descripcion=?, fecha=?, lugar=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$titulo, $descripcion, $fecha, $lugar, $id]);
                } else {
                    $sql = "INSERT INTO accidentes (titulo, descripcion, fecha, lugar) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$titulo, $descripcion, $fecha, $lugar]);
                }
                header("Location: crud_accidentes.php?mensaje=ok"); exit();
            } catch (PDOException $e) { $mensaje = "Error: " . $e->getMessage(); }
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("DELETE FROM accidentes WHERE id=?");
            $stmt->execute([$_GET['id']]);
            header("Location: crud_accidentes.php?mensaje=eliminado"); exit();
        }
        break;

    case 'formulario':
        $reg = ['id' => '', 'titulo' => '', 'descripcion' => '', 'fecha' => date('Y-m-d'), 'lugar' => ''];
        $titulo_form = "Registrar Accidente";
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM accidentes WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $reg = $stmt->fetch(PDO::FETCH_ASSOC);
            $titulo_form = "Editar Accidente";
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Accidentes</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body class="bg-light">
    <header class="header d-flex flex-row justify-content-between">
        <section>
            <h1 class="text">Modificar Accidentes</h1>
        </section>
        <section>
            <img src="../frontend/img/logo.png" alt="CBTis217">
        </section>
    </header>
    <div class="container mt-4">
        <a href="../frontend/usuario/dashboard.php" class="btn btn-secondary mb-3">⬅ Volver al Dashboard</a>
        
        <?php if ($accion == 'formulario'): ?>
            <div class="card p-4 shadow">
                <h3><?php echo $titulo_form; ?></h3>
                <form action="crud_accidentes.php?accion=guardar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                    <div class="mb-3">
                        <label>Título del suceso:</label>
                        <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($reg['titulo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Fecha:</label>
                        <input type="date" name="fecha" class="form-control" value="<?php echo htmlspecialchars($reg['fecha']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Lugar:</label>
                        <input type="text" name="lugar" class="form-control" value="<?php echo htmlspecialchars($reg['lugar']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Descripción detallada:</label>
                        <textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($reg['descripcion']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Guardar</button>
                    <a href="crud_accidentes.php" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🚑 Lista de Accidentes</h2>
                <a href="crud_accidentes.php?accion=formulario" class="btn btn-warning fw-bold">+ Nuevo Registro</a>
            </div>
            
            <div class="table-responsive bg-white p-3 shadow rounded">
                <table class="table table-hover">
                    <thead class="table-warning">
                        <tr><th>Fecha</th><th>Título</th><th>Lugar</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM accidentes ORDER BY fecha DESC");
                        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $fila['fecha'] . "</td>";
                            echo "<td>" . htmlspecialchars($fila['titulo']) . "</td>";
                            echo "<td>" . htmlspecialchars($fila['lugar']) . "</td>";
                            echo "<td>
                                <a href='crud_accidentes.php?accion=formulario&id={$fila['id']}' class='btn btn-sm btn-primary'>Editar</a>
                                <a href='crud_accidentes.php?accion=eliminar&id={$fila['id']}' onclick='return confirm(\"¿Borrar?\")' class='btn btn-sm btn-danger'>X</a>
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