<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Iniciar Sesión</h1>
            </section>
            <section>
                <img src="img/logo.png" alt="CBTis217">
            </section>
        </header>
        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">LOGIN</h2>
            </div>
            <section class="d-flex justify-content-center">
                <div class="login-container mt-4">
                    <?php
                    session_start();
                    $error = '';
                    $success = '';
                    if (isset($_SESSION['error'])) {
                        $error = $_SESSION['error'];
                        unset($_SESSION['error']);
                    }
                    
                    if (isset($_SESSION['success'])) {
                        $success = $_SESSION['success'];
                        unset($_SESSION['success']);
                    }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $username = $_POST['username'] ?? '';
                        $password = $_POST['password'] ?? '';
                        if (empty($username) || empty($password)) {
                            $error = "Por favor, completa todos los campos";
                        } else {
                            $valid_username = "admin";
                            $valid_password = "password123";
                            
                            if ($username === $valid_username && $password === $valid_password) {
                                $_SESSION['username'] = $username;
                                $_SESSION['logged_in'] = true;
                                header('Location: dashboard.php');
                                exit();
                            } else {
                                $error = "Usuario o contraseña incorrectos";
                            }
                        }
                    }
                    ?>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="login-form">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Ingresa tu usuario" required
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Ingresa tu contraseña" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        
                        <button type="submit" class="btn btn-login">Iniciar Sesión</button>
                        
                        <div class="text-center mt-3">
                            <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
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
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>