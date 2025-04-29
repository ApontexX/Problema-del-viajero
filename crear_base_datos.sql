CREATE DATABASE IF NOT EXISTS viajes;

USE viajes;

CREATE TABLE rutas (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       nombre VARCHAR(100) NOT NULL,
                       origen VARCHAR(2) NOT NULL,
                       destino VARCHAR(2) NOT NULL,
                       ruta TEXT NOT NULL,
                       costo_total INT NOT NULL,
                       fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE rutas ADD COLUMN matriz LONGTEXT;
