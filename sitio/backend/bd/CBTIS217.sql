DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(28) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fname VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'user') DEFAULT 'user'
);



INSERT INTO usuarios (username, pass, email, fname, rol) 
VALUES ('DAV23', '$2y$10$uzy5urX26/cO89MUDqnzrOw4vmAOQ3Hp6HcCfmxv8M2AwCYx23MWO', 'david@cbtis217.edu.mx', 'Administrador', 'admin');
INSERT INTO usuarios (username, pass, email, fname, rol) 
VALUES (
    'RAF28', 
    '$2y$10$uzy5urX26/cO89MUDqnzrOw4vmAOQ3Hp6HcCfmxv8M2AwCYx23MWO',
    'RAF28@email.com', 
    'Juan Rafael Gonzalez Diaz', 
    'admin'
);