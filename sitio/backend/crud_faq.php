<?php
session_start();
require_once '../frontend/usuario/configdatabase.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { die("Acceso denegado."); }

$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'guardar':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? '';
            // Asumiendo tabla: id, pregunta, respuesta, categoria
            $datos = [$_POST['pregunta'], $_POST['respuesta'], $_POST['categoria']];

            if (!empty($id)) {
                $sql = "UPDATE preguntas_frecuentes SET pregunta=?, respuesta=?, categoria=? WHERE id=?";
                $datos[] = $id; 
            } else {
                $sql = "INSERT INTO preguntas_frecuentes (pregunta, respuesta, categoria) VALUES (?, ?, ?)";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);
            header("Location: crud_faq.php"); exit();
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $pdo->prepare("DELETE FROM preguntas_frecuentes WHERE id=?")->execute([$_GET['id']]);
            header("Location: crud_faq.php"); exit();
        }
        break;

    case 'formulario':
        $reg = ['id'=>'', 'pregunta'=>'', 'respuesta'=>'', 'categoria'=>''];
        $titulo_form = "Nueva Pregunta";
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM preguntas_frecuentes WHERE id=?");
            $stmt->execute([$_GET['id']]);
            $reg = $stmt->fetch(PDO::FETCH_ASSOC);
            $titulo_form = "Editar Pregunta";
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de FAQ</title>
    <link rel="stylesheet" href="../frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body class="bg-light">
    <header class="header d-flex flex-row justify-content-between">
        <section>
            <h1 class="text">Modificar FAQ</h1>
        </section>
        <section>
            <img src="../frontend/img/logo.png" alt="CBTis217">
        </section>
    </header>
    <div class="container mt-4">
        <a href="../frontend/usuario/dashboard.php" class="btn btn-secondary mb-3">⬅ Volver al Dashboard</a>
        
        <?php if ($accion == 'formulario'): ?>
            <div class="card p-4 shadow border-success">
                <h3 class="text-success"><?php echo $titulo_form; ?></h3>
                <form action="crud_faq.php?accion=guardar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $reg['id']; ?>">
                    <div class="mb-3">
                        <label>Pregunta:</label>
                        <input type="text" name="pregunta" class="form-control" value="<?php echo htmlspecialchars($reg['pregunta']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Respuesta:</label>
                        <textarea name="respuesta" class="form-control" rows="4" required><?php echo htmlspecialchars($reg['respuesta']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Categoría (Seguridad, Legal, etc):</label>
                        <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($reg['categoria']); ?>">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Guardar FAQ</button>
                    <a href="crud_faq.php" class="btn btn-link w-100 mt-2">Cancelar</a>
                </form>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>❓ Preguntas Frecuentes</h2>
                <a href="crud_faq.php?accion=formulario" class="btn btn-success fw-bold">+ Nueva FAQ</a>
            </div>
            
            <div class="table-responsive bg-white p-3 shadow rounded">
                <table class="table table-hover">
                    <thead class="table-success">
                        <tr><th>Pregunta</th><th>Categoría</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM preguntas_frecuentes");
                        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($fila['pregunta']) . "</td>";
                            echo "<td><span class='badge bg-secondary'>" . htmlspecialchars($fila['categoria']) . "</span></td>";
                            echo "<td>
                                <a href='crud_faq.php?accion=formulario&id={$fila['id']}' class='btn btn-sm btn-primary'>Editar</a>
                                <a href='crud_faq.php?accion=eliminar&id={$fila['id']}' onclick='return confirm(\"¿Borrar?\")' class='btn btn-sm btn-danger'>X</a>
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