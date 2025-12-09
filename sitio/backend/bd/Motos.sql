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
) ENGINE = InnoDB;

CREATE TABLE accidentes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha VARCHAR (50) NOT NULL,
    lugar VARCHAR (50) NOT NULL,
    descripcion VARCHAR (50) NOT NULL,
    causa VARCHAR (50) NOT NULL,
    lesionados VARCHAR (50) NOT NULL,
    uso_casco VARCHAR (50) NOT NULL,
    nivel_gravedad VARCHAR (50) NOT NULL,
    evidencia BLOB NOT NULL
) ENGINE = InnoDB;

CREATE TABLE preguntas_frecuentes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta VARCHAR (50) NOT NULL,
    respuesta VARCHAR (50) NOT NULL,
    categoria VARCHAR (50) NOT NULL,
    orden VARCHAR (50) NOT NULL
)  ENGINE = InnoDB;

