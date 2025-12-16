CREATE DATABASE motos;
USE motos;

CREATE TABLE cascos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    Marca VARCHAR(100) NOT NULL,
    Modelo VARCHAR(100) NOT NULL,
    Tipo VARCHAR(100) NOT NULL,
    Certificacion VARCHAR(100) NOT NULL,
    Descripcion TEXT NOT NULL,
    Precio_aprox VARCHAR(20) NOT NULL,
    Imagen VARCHAR(255) NOT NULL,
    Fecha_registro DATE NOT NULL
) ENGINE = InnoDB;

CREATE TABLE accidentes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    lugar VARCHAR (100) NOT NULL,
    descripcion TEXT NOT NULL,
    causa VARCHAR (100) NOT NULL,
    lesionados INT NOT NULL,
    uso_casco BOOLEAN NOT NULL,
    nivel_gravedad VARCHAR (50) NOT NULL,
    evidencia VARCHAR(255) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE preguntas_frecuentes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    categoria VARCHAR (50) NOT NULL,
    orden VARCHAR (50) NOT NULL
)  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(28) UNIQUE NOT NULL,
    pass VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    fname VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'user') DEFAULT 'user'
) ENGINE = InnoDB;

INSERT INTO usuarios (username, pass, email, fname, rol) 
VALUES 
('DAV23', '$2y$10$uzy5urX26/cO89MUDqnzrOw4vmAOQ3Hp6HcCfmxv8M2AwCYx23MWO', 'david@example.com', 'David Almanza Lopez', 'admin'),
('RAF28', '$2y$10$uzy5urX26/cO89MUDqnzrOw4vmAOQ3Hp6HcCfmxv8M2AwCYx23MWO', 'rafael@example.com', 'Juan Rafael Gonzalez Diaz', 'admin');