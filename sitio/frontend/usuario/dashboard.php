<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: loging.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CBTis217</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard-container {
            padding: 40px 20px;
            text-align: center;
        }
        .welcome-message {
            background: linear-gradient(135deg, #4a6fa5, #2c4d7c);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Bienvenido</h1>
            </section>
            <section>
                <img src="img/logo.png" alt="CBTis217">
            </section>
        </header>
        
        <div class="dashboard-container">
            <div class="welcome-message">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p class="lead">Has iniciado sesión correctamente en el sistema CBTis217</p>
            </div>
            <div class="mt-5">
                <form method="POST" action="logout.php">
                    <button type="submit" class="btn-logout">Cerrar Sesión</button>
                </form>
            </div>
        </div>
        
        <footer class="footer p mt-5">
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
    
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>