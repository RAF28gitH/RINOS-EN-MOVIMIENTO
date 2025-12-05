CREATE DATABASE motos;
USE motos;

CREATE TABLE cascos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    Marca VARCHAR(50) NOT NULL,
    Modelo VARCHAR(50) NOT NULL,
    Tipo VARCHAR(50) NOT NULL,
    Certificacion VARCHAR(50) NOT NULL,
    Descripcion VARCHAR(50) NOT NULL,
    Precio_aprox VARCHAR(50) NOT NULL,
    Imagen BLOB NOT NULL,
    Fecha_registro DATE NOT NULL
);