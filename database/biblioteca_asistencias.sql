CREATE DATABASE biblioteca_asistencias;

/* CREAR Y ELIMINAR USUARIO
* **** MYSQL ****
* CREATE USER 'biblioteca'@'localhost' IDENTIFIED BY 'biblio';
* GRANT ALL PRIVILEGES ON biblioteca_asistencias.* TO 'biblioteca'@'localhost';
*
* **** MARIADB ****
* GRANT ALL PRIVILEGES ON biblioteca_asistencias.* TO 'biblioteca'@'localhost' IDENTIFIED BY 'biblio' WITH GRANT OPTION;
* DROP USER 'biblioteca'@'localhost';
*/

USE biblioteca_asistencias;

-- Es importante crear primero las tablas que no tienen claves foráneas (padres) y después las que sí tienen claves foráneas (hijas)

CREATE TABLE carrera (
	codigo VARCHAR(5) PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL
);

CREATE TABLE estudiante (
	numero_control CHAR(10) PRIMARY KEY, -- 8 dígitos exactos CHAR(8) porque no se relizan operaciones matemáticas con él, permite números de control como M20590123
	apellido_paterno VARCHAR(100) NOT NULL,
	apellido_materno VARCHAR(100),
	nombre VARCHAR(150) NOT NULL,
	codigo_carrera VARCHAR(5) NOT NULL,
	semestre TINYINT UNSIGNED CHECK (semestre BETWEEN 0 AND 12),
	genero ENUM('M', 'F') NOT NULL,
	FOREIGN KEY (codigo_carrera) REFERENCES carrera(codigo)
);

CREATE TABLE grupo (
	id TINYINT UNSIGNED PRIMARY KEY, -- TINYINT es ideal para valores pequeños como 1–7.
	nombre VARCHAR(100) NOT NULL
);

CREATE TABLE escuela (
	id SMALLINT UNSIGNED PRIMARY KEY, -- SMALLINT se usa ya que tienes valores como 509, fuera del rango de TINYINT.
	nombre VARCHAR(100) NOT NULL
);

CREATE TABLE usuario (
	numero_cuenta VARCHAR(15) PRIMARY KEY,
	nombre VARCHAR(200) NOT NULL,
	id_grupo TINYINT UNSIGNED NOT NULL,
	id_escuela SMALLINT UNSIGNED NOT NULL,
	genero ENUM('M', 'F') DEFAULT NULL,
	FOREIGN KEY (id_grupo) REFERENCES grupo(id),
	FOREIGN KEY (id_escuela) REFERENCES escuela(id)
);

CREATE TABLE asistencia (
	id INT AUTO_INCREMENT PRIMARY KEY,
	identificador VARCHAR(15) NOT NULL, -- número de control o cuenta
	nombre_completo VARCHAR(200) NOT NULL,
	carrera_area VARCHAR(100) NOT NULL,
	semestre TINYINT UNSIGNED CHECK (semestre BETWEEN 0 AND 12), -- NULL si es personal
	genero ENUM('M', 'F') DEFAULT NULL,
	fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
	INDEX idx_fecha_hora (fecha_hora) -- Índice en el campo fecha_hora
);

CREATE TABLE administrador (
	id INT AUTO_INCREMENT PRIMARY KEY,
	usuario VARCHAR(50) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL, -- Hash generado con password_hash
	creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Formato 2025-04-16 19:48:09
);

INSERT INTO carrera VALUES('II1','INGENIERIA INDUSTRIAL');
INSERT INTO carrera VALUES('IGE','INGENIERIA EN GESTION EMPRESARIAL');
INSERT INTO carrera VALUES('IS1','INGENIERIA EN SISTEMAS COMPUTACIONALES');
INSERT INTO carrera VALUES('ITI','INGENIERIA EN TECNOLOGIAS DE LA INFORMACION Y COMUNICACIONES');
INSERT INTO carrera VALUES('IEM','INGENIERIA ELECTROMECANICA');
INSERT INTO carrera VALUES('IE1','INGENIERIA ELECTRONICA');
INSERT INTO carrera VALUES('MCING','MAESTRIA EN CIENCIAS DE LA INGENIERIA');
INSERT INTO carrera VALUES('MPIAD','MAESTRIA EN INGENIERIA ADMINISTRATIVA');

INSERT INTO grupo VALUES(1,'ESTUDIANTE');
INSERT INTO grupo VALUES(2,'DOCENTE');
INSERT INTO grupo VALUES(3,'ADMINISTRATIVO');
INSERT INTO grupo VALUES(4,'INVESTIGADOR');
INSERT INTO grupo VALUES(5,'OTRO');
INSERT INTO grupo VALUES(7,'**GRUPO INEXISTENTE EN SIABUC8**');

INSERT INTO escuela VALUES(0,'**ESCUELA SIN NOMBRE**');
INSERT INTO escuela VALUES(1,'INGENIERIA INDUSTRIAL');
INSERT INTO escuela VALUES(2,'INGENIERIA EN SISTEMAS COMPUTACIONALES');
INSERT INTO escuela VALUES(3,'INGENIERIA INFORMATICA');
INSERT INTO escuela VALUES(4,'INGENIERIA ELECTRONICA');
INSERT INTO escuela VALUES(5,'DOCENTE');
INSERT INTO escuela VALUES(6,'ADMINISTRATIVO');
INSERT INTO escuela VALUES(7,'EGRESADO');
INSERT INTO escuela VALUES(8,'INGENIERIA EN GESTION EMPRESARIAL');
INSERT INTO escuela VALUES(9,'INGENIERIA EN TECNOLOGIAS DE LA INFORMACION Y COMUNICACIONES');
INSERT INTO escuela VALUES(10,'INGENIERIA ELECTROMECANICA');
INSERT INTO escuela VALUES(509,'**ESCUELA SIN NOMBRE**');