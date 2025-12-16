<?php
session_start();
require_once 'configdatabase.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $fname = $_POST['fname'] ?? '';
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($fname)) {
        $error = "Todos los campos son obligatorios";
    }
    elseif (strlen($username) > 28) {
        $error = "El nombre de usuario no puede exceder 28 caracteres";
    }
    elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    }
    elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    }
    else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            
            if ($stmt->fetch()) {
                $error = "El usuario o email ya están registrados";
            }
            else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, pass, email, fname, rol) 
                                      VALUES (:username, :pass, :email, :fname, 'user')");
                $stmt->execute([
                    'username' => $username,
                    'pass' => $hashed_password,
                    'email' => $email,
                    'fname' => $fname
                ]);
                
                $_SESSION['success'] = "¡Registro exitoso! Ahora puedes iniciar sesión";
                header('Location: login.php');
                exit();
            }
        } catch(PDOException $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Registrarse</h1>
            </section>
            <section>
                <img src="../img/logo.png" alt="CBTis217">
            </section>
        </header>
        <div>
            <div class="recuadro-page d-flex justify-content-center">
                <h2 class="text">REGISTRO</h2>
            </div>
            <section class="d-flex justify-content-center">
                <div class="registration-container mt-4">
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="registration-form">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="fname" name="fname" 
                                   placeholder="Ingresa tu nombre completo" required
                                   value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Ingresa tu email" required
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario * (máx. 28 caracteres)</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Elige un nombre de usuario" required maxlength="28"
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Mínimo 6 caracteres" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Repite tu contraseña" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                            <a href="login.php" class="btn btn-secondary">¿Ya tienes cuenta? Inicia Sesión</a>
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