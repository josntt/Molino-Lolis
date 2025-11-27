drop database if exists molino;
create database molino;
use molino;


-- Borrado de tablas en orden inverso para evitar errores de clave foránea
DROP TABLE IF EXISTS contacto;
DROP TABLE IF EXISTS pregunta_cliente;
DROP TABLE IF EXISTS faq;
DROP TABLE IF EXISTS avisos;
DROP TABLE IF EXISTS horarios_molida;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS trabajador;
DROP TABLE IF EXISTS administrador;
DROP TABLE IF EXISTS servicios_ofrecidos;

-- -----------------------------------------------------
-- Tabla administrador
-- -----------------------------------------------------
CREATE TABLE administrador (
  idAdmin INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(150) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  telefono VARCHAR(20) NULL,
  PRIMARY KEY (idAdmin),
  UNIQUE INDEX correo_UNIQUE (correo ASC)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla trabajador
-- -----------------------------------------------------
CREATE TABLE trabajador (
  idTrabajador INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  genero VARCHAR(50) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  telefono VARCHAR(20) NULL,
  puesto VARCHAR(50) NULL,
  PRIMARY KEY (idTrabajador),
  UNIQUE INDEX correo_UNIQUE (correo ASC)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla cliente
-- -----------------------------------------------------
CREATE TABLE cliente (
  idCliente INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(150) NOT NULL,
  genero VARCHAR(50) NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  telefono VARCHAR(20) NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (idCliente),
  UNIQUE INDEX correo_UNIQUE (correo ASC)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla productos
-- -----------------------------------------------------
CREATE TABLE productos (
  id_producto INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  imagen VARCHAR(255) NULL,
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_producto)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla servicios_ofrecidos
-- -----------------------------------------------------
CREATE TABLE servicios_ofrecidos (
  id_servicio INT NOT NULL AUTO_INCREMENT,
  tipo ENUM('Entrega','Recepción','Envío','Otro') NOT NULL,
  nombre_servicio VARCHAR(150) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  horario_inicio TIME DEFAULT NULL,
  horario_fin TIME DEFAULT NULL,
  dias_disponibles VARCHAR(100) DEFAULT NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_servicio)
) ENGINE=InnoDB;
-- -----------------------------------------------------
-- Tabla horarios_molida
-- -----------------------------------------------------
CREATE TABLE horarios_molida (
  id_horario INT NOT NULL AUTO_INCREMENT,
  fecha DATE NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  tipo_molida VARCHAR(100) NOT NULL,
  id_producto INT NOT NULL,
  observaciones VARCHAR(255) NULL,
  id_admin_creador INT NULL,
  id_trabajador_creador INT NULL,
  PRIMARY KEY (id_horario),
  CONSTRAINT fk_horarios_producto
    FOREIGN KEY (id_producto)
    REFERENCES productos (id_producto)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_horarios_admin
    FOREIGN KEY (id_admin_creador)
    REFERENCES administrador (idAdmin)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_horarios_trabajador
    FOREIGN KEY (id_trabajador_creador)
    REFERENCES trabajador (idTrabajador)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla avisos
-- -----------------------------------------------------
CREATE TABLE avisos (
  id_aviso INT NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(150) NOT NULL,
  contenido TEXT NOT NULL,
  fecha_publicacion DATE NOT NULL,
  autor_id_admin INT NULL,
  PRIMARY KEY (id_aviso),
  CONSTRAINT fk_avisos_admin
    FOREIGN KEY (autor_id_admin)
    REFERENCES administrador (idAdmin)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla faq (Preguntas Frecuentes)
-- -----------------------------------------------------
CREATE TABLE faq (
  id_faq INT NOT NULL AUTO_INCREMENT,
  pregunta VARCHAR(255) NOT NULL,
  respuesta TEXT NOT NULL,
  visible TINYINT(1) NOT NULL DEFAULT 1,
  creado_por_admin INT NULL,
  PRIMARY KEY (id_faq),
  CONSTRAINT fk_faq_admin
    FOREIGN KEY (creado_por_admin)
    REFERENCES administrador (idAdmin)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla pregunta_cliente
-- -----------------------------------------------------
CREATE TABLE pregunta_cliente (
  id_pregunta INT NOT NULL AUTO_INCREMENT,
  id_cliente INT NULL,
  pregunta_texto TEXT NOT NULL,
  respuesta_texto TEXT NULL,
  fecha_pregunta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_respuesta DATETIME NULL,
  id_trabajador_respuesta INT NULL,
  id_admin_respuesta INT NULL,
  estado ENUM('pendiente', 'respondida') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (id_pregunta),
  CONSTRAINT fk_pregunta_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES cliente (idCliente)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_pregunta_trabajador_resp
    FOREIGN KEY (id_trabajador_respuesta)
    REFERENCES trabajador (idTrabajador)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_pregunta_admin_resp
    FOREIGN KEY (id_admin_respuesta)
    REFERENCES administrador (idAdmin)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla contacto
-- -----------------------------------------------------
CREATE TABLE contacto (
  id_contacto INT NOT NULL DEFAULT 1,
  telefono VARCHAR(15) NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  correo_contacto VARCHAR(100) NOT NULL,
  url_facebook VARCHAR(255) NULL,
  actualizado_por_admin INT NULL,
  PRIMARY KEY (id_contacto),
  CONSTRAINT fk_contacto_admin
    FOREIGN KEY (actualizado_por_admin)
    REFERENCES administrador (idAdmin)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB;




-- Inserción de usuarios Admin y trabajadores del molino

INSERT INTO administrador (nombre, apellidos, correo, contrasena, telefono)
VALUES 
('Gloria', 'Torres Martínez', 'moyitatorres9@gmail.com', '1234567890', '7778360154');

UPDATE administrador
SET contrasena = '$2y$10$xtMfcm46qVJDxcXkf5aUmu2pLqe0oyvuLV.khwZWTo4kGN8uads1e'
WHERE correo = 'moyitatorres9@gmail.com';

-- Datos de los trabajadores
INSERT INTO trabajador (nombre, apellidos, genero, correo, contrasena, telefono,puesto) VALUES
('Eduardo', ' Mauro', 'Masculino','eduardo@molinololis.com', ' ', '7778000001', 'Encargado'),
('María',' Dolores Martínez', 'Femenino', 'maria@molinololis.com', ' ', '7778000002', 'Ayudante general'),
('Gregorio',' Escobar', 'Masculino', 'gregorio@molinololis.com', ' ', '7778000003', 'Ayudante general'),
('Danitza',' Dolores Martínez', 'Femenino', 'danitza@molinololis.com', ' ', '7778000004', 'Ayudante general'),
('Josue',' Torres Martínez', 'Masculino','josue@molinololis.com', ' ', '7778000001', 'Ayudante general');

UPDATE trabajador
SET contrasena = '$2y$10$smiDmq1a3O.4DEIzhCcjF.TBoAM9GPx7g0.WbOa0Tm1RtgAOptkRC'
WHERE correo IN (
    'eduardo@molinololis.com',
    'maria@molinololis.com',
    'gregorio@molinololis.com',
    'danitza@molinololis.com',
    'josue@molinololis.com'
);




