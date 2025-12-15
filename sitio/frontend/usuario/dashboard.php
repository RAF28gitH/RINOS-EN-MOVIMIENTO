<?php
session_start();
require_once 'configdatabase.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$is_admin = ($_SESSION['rol'] === 'admin');
if ($is_admin) {
    try {
        $stmt = $pdo->query("SELECT id, username, email, fname, rol FROM usuarios ORDER BY id ASC");
        $usuarios = $stmt->fetchAll();
    } catch(PDOException $e) {
        $error_usuarios = "Error al cargar usuarios: " . $e->getMessage();
    }
}
if ($is_admin && isset($_POST['action'])) {
    $user_id = $_POST['user_id'] ?? '';
    $action = $_POST['action'];
    
    if ($action === 'delete' && $user_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id AND rol != 'admin'");
            $stmt->execute(['id' => $user_id]);
            $_SESSION['success'] = "Usuario eliminado correctamente";
            header('Location: dashboard.php');
            exit();
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error al eliminar usuario: " . $e->getMessage();
            header('Location: dashboard.php');
            exit();
        }
    } elseif ($action === 'update_role' && $user_id) {
        $new_role = $_POST['rol'] ?? 'user';
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET rol = :rol WHERE id = :id");
            $stmt->execute(['rol' => $new_role, 'id' => $user_id]);
            $_SESSION['success'] = "Rol actualizado correctamente";
            header('Location: dashboard.php');
            exit();
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error al actualizar rol: " . $e->getMessage();
            header('Location: dashboard.php');
            exit();
        }
    }
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
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
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
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .role-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .role-admin {
            background-color: #dc3545;
            color: white;
        }
        .role-user {
            background-color: #28a745;
            color: white;
        }
        .user-info-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="principal d-flex flex-column">
        <header class="header d-flex flex-row justify-content-between">
            <section>
                <h1 class="text">Dashboard</h1>
                <small class="text-white">Rol: <?php echo htmlspecialchars($_SESSION['rol']); ?></small>
            </section>
            <section>
                <img src="img/logo.png" alt="CBTis217">
            </section>
        </header>
        
        <div class="dashboard-container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="welcome-message">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</h2>
                <p class="lead">Usuario: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                <?php if ($is_admin): ?>
                    <p class="mb-0"><strong>🔧 Tienes permisos de administrador</strong></p>
                <?php else: ?>
                    <p class="mb-0"><strong>👤 Eres un usuario regular</strong></p>
                <?php endif; ?>
            </div>
            
            <?php if ($is_admin): ?>
            <!-- Panel de Administración -->
            <div class="table-container">
                <h3>Gestión de Usuarios</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['fname']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo htmlspecialchars($usuario['rol']); ?>">
                                            <?php echo htmlspecialchars($usuario['rol']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($usuario['rol'] !== 'admin'): ?>
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('¿Cambiar rol de este usuario?');">
                                            <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                                            <input type="hidden" name="action" value="update_role">
                                            <select name="rol" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="user" <?php echo $usuario['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
                                                <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </form>
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                            <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                        <?php else: ?>
                                        <span class="text-muted">Administrador principal</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php elseif (isset($error_usuarios)): ?>
                                <tr>
                                    <td colspan="6" class="text-danger"><?php echo htmlspecialchars($error_usuarios); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h5>Funciones de Administrador:</h5>
                    <ul>
                        <li>Ver todos los usuarios registrados</li>
                        <li>Cambiar roles entre "user" y "admin"</li>
                        <li>Eliminar usuarios (excepto otros administradores)</li>
                        <li>Gestionar la base de datos completa</li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!$is_admin): ?>
            <div class="user-info-card">
                <h3>Tu Información Personal</h3>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Nombre Completo:</strong><br>
                        <?php echo htmlspecialchars($_SESSION['fname']); ?></p>
                        
                        <p><strong>Usuario:</strong><br>
                        <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Rol en el sistema:</strong><br>
                        <span class="role-badge role-user">Usuario Regular</span></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Funciones Disponibles:</h5>
                    <ul>
                        <li>Ver tu información personal</li>
                        <li>Acceder a funciones básicas del sistema</li>
                        <li>Cambiar tu contraseña (si implementas esta función)</li>
                        <li>Solicitar ayuda a los administradores</li>
                    </ul>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Nota:</strong> Solo los administradores pueden modificar la base de datos de usuarios. 
                        Si necesitas cambios, contacta a un administrador.
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-5">
                <form method="POST" action="logout.php" class="d-inline">
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