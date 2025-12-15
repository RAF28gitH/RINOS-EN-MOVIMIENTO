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
    Imagen BLOB NOT NULL,
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
    evidencia BLOB NOT NULL
) ENGINE = InnoDB;

CREATE TABLE preguntas_frecuentes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    categoria VARCHAR (50) NOT NULL,
    orden VARCHAR (50) NOT NULL
)  ENGINE = InnoDB;