CREATE DATABASE IF NOT EXISTS finishline_db CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE finishline_db;

CREATE TABLE IF NOT EXISTS Autenticacion (
    id_autenticacion INT AUTO_INCREMENT PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL
    );

CREATE TABLE IF NOT EXISTS Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_autenticacion INT,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Telefono VARCHAR(20),
    Correo VARCHAR(150) NOT NULL,
    Rol VARCHAR(50) DEFAULT 'cliente',
    FOREIGN KEY (id_autenticacion) REFERENCES Autenticacion(id_autenticacion)
    );

CREATE TABLE IF NOT EXISTS Servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    disponible BOOLEAN DEFAULT TRUE,
    categoria_vehiculo VARCHAR(100)
    );

CREATE TABLE IF NOT EXISTS Ticket (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_servicio INT,
    descripcion TEXT,
    matricula VARCHAR(20),
    fecha_inicio DATE,
    fecha_fin DATE,
    presupuesto DECIMAL(10,2),
    estado VARCHAR(50) DEFAULT 'pendiente',
    fecha_cita DATETIME,
    modelo_auto VARCHAR(20),
    id_empleado INT,
    descripcion_trabajo TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio),
    FOREIGN KEY (id_empleado) REFERENCES Usuario(id_usuario) ON DELETE SET NULL
    );

CREATE TABLE IF NOT EXISTS Nota_X_Ticket (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    id_usuario INT NOT NULL,
    nota TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Respuesta_x_ticket (
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_ticket INT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha_respuesta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS Solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(150),
    asunto VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente','en_proceso','respondida') DEFAULT 'pendiente'
    );

CREATE TABLE IF NOT EXISTS Respuesta_x_solicitud (
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha_respuesta DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_solicitud (id_solicitud),
    FOREIGN KEY (id_solicitud) REFERENCES Solicitudes(id_solicitud) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS documentos (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    ruta VARCHAR(255),
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE IF NOT EXISTS documento_x_tickets (
    id_documento INT NOT NULL,
    id_ticket INT NOT NULL,
    PRIMARY KEY (id_documento, id_ticket),
    FOREIGN KEY (id_documento) REFERENCES documentos(id_documento) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_ticket) REFERENCES Ticket(id_ticket) ON DELETE CASCADE ON UPDATE CASCADE
    );

CREATE TABLE IF NOT EXISTS password_reset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expira_en DATETIME NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
    );