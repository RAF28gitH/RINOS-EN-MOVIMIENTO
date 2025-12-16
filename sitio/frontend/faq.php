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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> </head>

<body>

    <div class="principal d-flex flex-column">
        
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Preguntas Frecuentes</h1>
            </section>
            <section>
                <img src="https://cbtis217.edu.mx/recursos/img/logo.png" alt="Logo CBTis 217" style="max-height: 80px;">
            </section>
        </header>

        <nav class="navvv p">
            <ul class="nav nav-pills justify-content-end">
                <li class="nav-item"><a class="nav-link" href="inicio.html">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="practicas-seguras.html">Prácticas Seguras</a></li>
                <li class="nav-item"><a class="nav-link" href="tipos-cascos.html">Tipos De Casco</a></li>
                <li class="nav-item"><a class="nav-link" href="normativa.html">Normativa Vial</a></li>
                <li class="nav-item"><a class="nav-link" href="accidentes.html">Accidentes Viales</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="faq.php">Preguntas frecuentes</a></li>
                <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
            </ul>
        </nav>

        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">DUDAS COMUNES</h2>
            </div>
            
            <section class="d-flex justify-content-center flex-column align-items-center">
                
                <div class="container my-4 faq-info" style="padding: 1rem; border-radius: 20px; background-color: rgba(255, 255, 255, 0.1);">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="d-flex align-items-center justify-content-center">
                        <label for="cat" class="text me-3"><strong>Filtrar por tema:</strong></label>
                        <select name="cat" id="cat" onchange="this.form.submit()" class="form-select w-auto">
                            <option value="">Todas las categorías</option>
                            <?php
                            if ($resCategorias->num_rows > 0) {
                                $resCategorias->data_seek(0); // Reiniciar puntero si es necesario
                                while ($catRow = $resCategorias->fetch_assoc()) {
                                    $nombreCat = $catRow['categoria'];
                                    $selected = ($filtro == $nombreCat) ? 'selected' : '';
                                    echo "<option value='$nombreCat' $selected>$nombreCat</option>";
                                }
                            }
                            ?>
                        </select>
                    </form>
                </div>

                
                    
                    <div class="mx-auto faq-info"> 
                        
                        <div class="borde-accordion">
                            <div class="accordion accordion-flush" id="accordionFaq">
                                
                                <?php
                                if ($resultado->num_rows > 0) {
                                    while ($fila = $resultado->fetch_assoc()) {
                                        $idPregunta = $fila['id'];
                                        $preguntaSegura = htmlspecialchars($fila['pregunta']);
                                        $respuestaSegura = nl2br(htmlspecialchars($fila['respuesta']));
                                        $categoriaSegura = htmlspecialchars($fila['categoria']);
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $idPregunta; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $idPregunta; ?>" aria-expanded="false" aria-controls="collapse<?php echo $idPregunta; ?>">
                                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                                                    <?php if (!empty($categoriaSegura)): ?>
                                                        <span class="badge bg-warning text-dark me-2 mb-1 mb-md-0"><?php echo $categoriaSegura; ?></span>
                                                    <?php endif; ?>
                                                    <strong class="text"><?php echo $preguntaSegura; ?></strong>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $idPregunta; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $idPregunta; ?>" data-bs-parent="#accordionFaq">
                                            <div class="accordion-body text" style="text-align: justify;">
                                                <?php echo $respuestaSegura; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    }
                                } else {
                                    echo "<div class='p-4 text-center text'><p>No se encontraron preguntas en esta categoría.</p>";
                                    if ($filtro != "") {
                                        echo "<a href='faq.php' class='btn btn-light btn-sm'>Ver todas</a></div>";
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                

            </section>
        </div>

        <footer class="footer p mt-auto">
            <h4 class="text">Página de CBTis217</h4>
            <p class="text">Derechos reservados &copy; <?php echo date("Y"); ?></p>
        </footer>

    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>