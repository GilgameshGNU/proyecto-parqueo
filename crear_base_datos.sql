-- Crear nueva base de datos para el proyecto de parqueo
CREATE DATABASE IF NOT EXISTS Parqueo;
USE Parqueo;

-- Tabla de Membresías
CREATE TABLE Membresia
(
    IdMembresia INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Descuento DECIMAL(5,2), -- porcentaje de descuento
    FechaInicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FechaFin DATETIME NULL,
    Estado ENUM('Activa','Inactiva') DEFAULT 'Activa',
    Precio DECIMAL(10,2) NOT NULL
);

-- Tabla de Clientes
CREATE TABLE Cliente
(
    IdCliente INT AUTO_INCREMENT PRIMARY KEY,
    NombreCompleto VARCHAR(100) NOT NULL,
    Telefono VARCHAR(15),
    Ci VARCHAR(15),
    IdMembresia INT NULL,
    FOREIGN KEY (IdMembresia) REFERENCES Membresia(IdMembresia)
);

-- Tabla de Tipos de Vehículo
CREATE TABLE TipoVehiculo
(
    IdTipo INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(30) NOT NULL -- Moto, Auto, Camioneta, etc.
);

-- Tabla de Tarifas
CREATE TABLE Tarifa
(
    IdTarifa INT AUTO_INCREMENT PRIMARY KEY,
    IdTipo INT NOT NULL,
    Precio DECIMAL(10,2) NOT NULL,
    FechaInicio DATE NOT NULL DEFAULT (CURDATE()),
    FechaFin DATE NULL,
    FOREIGN KEY (IdTipo) REFERENCES TipoVehiculo(IdTipo)
);

-- Tabla de Vehículos
CREATE TABLE Vehiculo
(
    IdVehiculo INT AUTO_INCREMENT PRIMARY KEY,
    Placa VARCHAR(15) NOT NULL,
    Modelo VARCHAR(30),
    Marca VARCHAR(30),
    Color VARCHAR(30),
    IdTipo INT,
    FOREIGN KEY (IdTipo) REFERENCES TipoVehiculo(IdTipo)
);

-- Relación muchos a muchos (Cliente - Vehículos)
CREATE TABLE ClienteVehiculos
(
    IdClienteVeh INT AUTO_INCREMENT PRIMARY KEY,
    IdCliente INT,
    IdVehiculo INT,
    FOREIGN KEY (IdCliente) REFERENCES Cliente(IdCliente),
    FOREIGN KEY (IdVehiculo) REFERENCES Vehiculo(IdVehiculo)
);

-- Tabla de Espacios de Parqueo
CREATE TABLE EspacioParqueo
(
    IdEspacioParqueo INT AUTO_INCREMENT PRIMARY KEY,
    NumeroEspacio VARCHAR(20) NOT NULL,
    Estado ENUM('Disponible','Mantenimiento') DEFAULT 'Disponible',
    Zona VARCHAR(30) NOT NULL,
    IdTipo INT, -- tipo de vehículo permitido en ese espacio
    FOREIGN KEY (IdTipo) REFERENCES TipoVehiculo(IdTipo)
);

-- Tabla de Tickets
CREATE TABLE Ticket
(
    IdTicket INT AUTO_INCREMENT PRIMARY KEY,
    FechaHoraEntrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    FechaHoraSalida DATETIME NULL,
    Estado ENUM('Abierto','Cerrado') DEFAULT 'Abierto',
    CostoTotal DECIMAL(10,2),
    IdClienteVeh INT,
    IdEspacioParqueo INT,
    FOREIGN KEY (IdClienteVeh) REFERENCES ClienteVehiculos(IdClienteVeh),
    FOREIGN KEY (IdEspacioParqueo) REFERENCES EspacioParqueo(IdEspacioParqueo)
);

-- Tabla de Pagos
CREATE TABLE Pago
(
    IdPago INT AUTO_INCREMENT PRIMARY KEY,
    Nit VARCHAR(20),
    RazonSocial VARCHAR(50),
    FechaPago DATE DEFAULT (CURDATE()),
    MontoTotal DECIMAL(10,2),
    MetodoPago ENUM('Efectivo','QR') NOT NULL,
    Estado ENUM('Pendiente','Pagado','Anulado') DEFAULT 'Pendiente',
    IdTicket INT,
    FOREIGN KEY (IdTicket) REFERENCES Ticket(IdTicket)
);

-- Insertar tipos de vehículo iniciales
INSERT INTO TipoVehiculo(Nombre) VALUES
('Moto'),
('Auto Estandar'),
('Auto Grande');

-- Insertar tarifas iniciales
INSERT INTO Tarifa(IdTipo, Precio, FechaInicio) VALUES
(1, 1.00, CURDATE()),
(2, 2.00, CURDATE()),
(3, 3.00, CURDATE());

-- Insertar algunas membresías de ejemplo
INSERT INTO Membresia(Nombre, Descuento, Precio) VALUES
('Básica', 5.00, 50.00),
('Premium', 15.00, 100.00),
('VIP', 25.00, 200.00);

-- Mostrar mensaje de confirmación
SELECT 'Base de datos Parqueo creada exitosamente!' as Mensaje;
