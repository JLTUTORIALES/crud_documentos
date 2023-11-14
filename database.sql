DROP DATABASE IF EXISTS CRUD_DOCUMENTOS;
CREATE DATABASE CRUD_DOCUMENTOS;
USE CRUD_DOCUMENTOS;

CREATE TABLE PRO_PROCESO(
    PRO_ID INT PRIMARY KEY AUTO_INCREMENT,
    PRO_PREFIJO VARCHAR(3) NOT NULL,
    PRO_NOMBRE VARCHAR(60) NOT NULL
);

CREATE TABLE TIP_TIPO_DOC(
    TIP_ID INT PRIMARY KEY AUTO_INCREMENT,
    TIP_PREFIJO VARCHAR(3) NOT NULL,
    TIP_NOMBRE VARCHAR(60) NOT NULL
);

CREATE TABLE DOC_DOCUMENTO(
    DOC_ID INT PRIMARY KEY AUTO_INCREMENT,
    DOC_NOMBRE VARCHAR(20) NOT NULL,
    DOC_CODIGO VARCHAR(20) UNIQUE,
    DOC_CONTENIDO MEDIUMTEXT NOT NULL,
    DOC_ID_TIPO INT NOT NULL,
    DOC_ID_PROCESO INT NOT NULL,
    DELETED INT(1) DEFAULT 0
);

CREATE TABLE USERS(
    ID_USER INT PRIMARY KEY AUTO_INCREMENT,
    NAME VARCHAR(20) NOT NULL,
    USER_NAME VARCHAR(20) NOT NULL UNIQUE,
    PASSWORD VARCHAR(60) NOT NULL
);

-- lLAVE FORANEA
ALTER TABLE DOC_DOCUMENTO ADD CONSTRAINT FK_DOC_TIPO FOREIGN KEY (DOC_ID_TIPO) REFERENCES TIP_TIPO_DOC(TIP_ID);
ALTER TABLE DOC_DOCUMENTO ADD CONSTRAINT FK_DOC_PROCESO FOREIGN KEY (DOC_ID_PROCESO) REFERENCES PRO_PROCESO(PRO_ID);

-- USUARIOS PRECARGADOS

INSERT INTO USERS VALUES
(NULL, 'Jose Luis Ortiz', 'jortiz', '$2y$10$LyMQNoGXEqX.HDcE1wPPnOCNMCws0paCCcKhUb3vmoRKT4IWBD99y'),
(NULL, 'Miguel Mateos', 'mmateos', '$2y$10$Qce5BjLdLGoBBonQLp3qUOO2P81nCyOVCjcgUWynrDLUmOTeA5pD.');

-- PROCESOS PRECARGADOS

INSERT INTO PRO_PROCESO VALUES
(NULL, 'ING', 'Ingeniería'),
(NULL, 'ARQ', 'Arquitectura'),
(NULL, 'PSI', 'Psicología'),
(NULL, 'ART', 'Arte'),
(NULL, 'SCI', 'Ciencia');

-- TIPO DE DOCUMENTOS PRECARGADOS

INSERT INTO TIP_TIPO_DOC VALUES
(NULL, 'INS', 'Instructivo'),
(NULL, 'ART', 'Artículo'),
(NULL, 'NOT', 'Noticia'),
(NULL, 'ENS', 'Ensayo'),
(NULL, 'GLO', 'Glosario');

CREATE USER crud_documentos IDENTIFIED BY 'crud_documentos';
GRANT ALL PRIVILEGES ON CRUD_DOCUMENTOS.* TO crud_documentos;