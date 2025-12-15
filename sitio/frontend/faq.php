<?php
require_once '../backend/conexion.php';

$sqlCategorias = "SELECT DISTINCT categoria FROM faqs WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
$resCategorias = $conn->query($sqlCategorias);
$filtro = isset($_GET['cat']) ? $_GET['cat'] : '';
$sql = "SELECT id, pregunta, respuesta, categoria FROM faqs";

if ($filtro != "") {
    // Si hay filtro, agregar la cláusula WHERE
    $sql .= " WHERE categoria = ?";
    $stmt = $conn->prepare($sql . " ORDER BY orden ASC");
    $stmt->bind_param("s", $filtro);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    // Si no hay filtro, traer todo
    $sql .= " ORDER BY orden ASC";
    $resultado = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - Seguridad Vial</title>
    <link rel="stylesheet" href="css/faq.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="https://cbtis217.edu.mx/recursos/img/logo.png" alt="Logo CBTis 217">
        </div>
        <ul class="nav-links">
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Prácticas Seguras</a></li>
            <li><a href="#">Tipos de Cascos</a></li>
            <li><a href="#">Normativa Vial</a></li>
            <li><a href="#">Accidentes Moto</a></li>
            <li><a href="#" class="active">FAQ</a></li>
            <li><a href="#">Contacto</a></li>
            <li class="auth-item"><a href="#" class="login-btn">Login</a></li>
            <li class="auth-item"><a href="#" class="register-btn">Registro</a></li>
        </ul>
    </nav>

    <main class="faq-container">
        <h1>Preguntas Frecuentes (FAQ)</h1>
        <p class="faq-intro">Respuestas a las dudas más comunes, cargadas directamente desde nuestra base de datos.</p>

        <div class="filter-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <label for="cat">Filtrar por tema:</label>
                <select name="cat" id="cat" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>

                    <?php
                    // Llenar el combobox con las categorías de la BD
                    if ($resCategorias->num_rows > 0) {
                        while ($catRow = $resCategorias->fetch_assoc()) {
                            $nombreCat = $catRow['categoria'];
                            // Si la categoría actual es igual a la que está en la URL, se deja seleccionada
                            $selected = ($filtro == $nombreCat) ? 'selected' : '';
                            echo "<option value='$nombreCat' $selected>$nombreCat</option>";
                        }
                    }
                    ?>
                </select>
            </form>
        </div>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $preguntaSegura = htmlspecialchars($fila['pregunta']);
                $respuestaSegura = nl2br(htmlspecialchars($fila['respuesta']));
                $categoriaSegura = htmlspecialchars($fila['categoria']);
        ?>
                <details class="faq-item">
                    <summary>
                        <?php if (!empty($categoriaSegura)): ?>
                            <span class="cat-badge"><?php echo $categoriaSegura; ?></span>
                        <?php endif; ?>

                        <?php echo $preguntaSegura; ?>
                    </summary>
                    <div class="faq-answer">
                        <p><?php echo $respuestaSegura; ?></p>
                    </div>
                </details>
        <?php
            }
        } else {
            echo "<p style='text-align:center; margin-top:20px;'>No se encontraron preguntas en esta categoría.</p>";
            // Botón para limpiar filtro si no hay resultados
            if ($filtro != "") {
                echo "<p style='text-align:center'><a href='index.php'>Ver todas las preguntas</a></p>";
            }
        }

        $conn->close();
        ?>

    </main>

</body>

</html>