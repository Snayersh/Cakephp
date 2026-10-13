SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
-- ============================================================================
-- SISTEMA DE GESTIÓN DE PERSONAL STERENFIX
-- Script DDL: Definición de Esquema y Tablas para MySQL 8.0
-- ============================================================================

CREATE DATABASE IF NOT EXISTS sterenfix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sterenfix;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS llamadas_atencion;
DROP TABLE IF EXISTS logros;
DROP TABLE IF EXISTS empleados;
DROP TABLE IF EXISTS puestos;
DROP TABLE IF EXISTS tiendas;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Tabla TIENDAS (Catálogo)
CREATE TABLE tiendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) NULL,
    telefono VARCHAR(20) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_tiendas_nombre UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabla PUESTOS (Catálogo)
CREATE TABLE puestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_puestos_nombre UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla EMPLEADOS
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    fotografia VARCHAR(255) NULL,
    salario DECIMAL(12, 2) NOT NULL,
    puesto_id INT NOT NULL,
    tienda_id INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_empleados_salario CHECK (salario > 0),
    CONSTRAINT fk_empleados_puesto FOREIGN KEY (puesto_id) REFERENCES puestos(id) ON DELETE CASCADE,
    CONSTRAINT fk_empleados_tienda FOREIGN KEY (tienda_id) REFERENCES tiendas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla LOGROS
CREATE TABLE logros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    tipo ENUM('positivo', 'negativo') NOT NULL,
    fecha_ocurrencia DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logros_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla LLAMADAS_ATENCION
CREATE TABLE llamadas_atencion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    fecha_ocurrencia DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_llamadas_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para optimización de consultas y reportes
CREATE INDEX idx_empleados_tienda ON empleados(tienda_id);
CREATE INDEX idx_empleados_puesto ON empleados(puesto_id);
CREATE INDEX idx_logros_empleado ON logros(empleado_id);
CREATE INDEX idx_logros_tipo ON logros(tipo);
CREATE INDEX idx_llamadas_empleado ON llamadas_atencion(empleado_id);
CREATE INDEX idx_llamadas_fecha ON llamadas_atencion(fecha_ocurrencia);
