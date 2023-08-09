CREATE DATABASE bd_desis;
USE bd_desis;
CREATE TABLE Region (
	Region_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Candidato (
	Candidato_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Comuna (
	Comuna_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Region_id INT NOT NULL,
	Nombre VARCHAR(50) NOT NULL,
    CONSTRAINT fk_Region_Comuna FOREIGN KEY (Region_id) REFERENCES Region(Region_id)
);

CREATE TABLE Votos (
	Votos_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Nombre VARCHAR(50) NOT NULL,
	Alias VARCHAR(50) NOT NULL,
	Rut VARCHAR(10) NOT NULL,
	Email VARCHAR(50) NOT NULL,
	Region_id INT NOT NULL,
	Comuna_id INT NOT NULL,
    Candidato_id INT NOT NULL,
	Nosotros VARCHAR(50) NOT NULL,
    CONSTRAINT fk_Votos_Region FOREIGN KEY (Region_id) REFERENCES region(Region_id),
    CONSTRAINT fk_Votos_Comuna FOREIGN KEY (Comuna_id) REFERENCES comuna(Comuna_id),
    CONSTRAINT fk_Votos_Candidato FOREIGN KEY (Candidato_id) REFERENCES candidato(Candidato_id)
);


INSERT INTO region (Region_id, Nombre) VALUES (1, 'IV');
INSERT INTO region (Region_id, Nombre) VALUES (2, 'V');
INSERT INTO region (Region_id, Nombre) VALUES (3, 'Metropolitana');

INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (1, 1, 'Valparaíso');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (2, 1, 'Viña del Mar');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (3, 1, 'Quintero');

INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (4, 2, 'Codegua');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (5, 2, 'Coinco');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (6, 2, 'Graneros');

INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (7, 3, 'Puente Alto');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (8, 3, 'La Pintana');
INSERT INTO comuna (Comuna_id, Region_id, Nombre) VALUES (9, 3, 'La Florida');

INSERT INTO candidato (Candidato_id, Nombre) VALUES (1, 'Jose Perez');
INSERT INTO candidato (Candidato_id, Nombre) VALUES (2, 'Pablo Escobar');
INSERT INTO candidato (Candidato_id, Nombre) VALUES (3, 'Freddy Mercury');