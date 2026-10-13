SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
-- ============================================================================
-- SISTEMA DE GESTIÓN DE PERSONAL STERENFIX
-- Script DML: Inserción de Datos Iniciales (Seeds / Fixtures) para MySQL 8.0
-- ============================================================================
USE sterenfix;

-- 1. Inserción de Tiendas
INSERT INTO tiendas (id, nombre, direccion, telefono) 
VALUES (1, 'Sterenfix Matriz Centro', 'Av. Juárez 1050, Centro Histórico, CDMX', '+502 2234 5678');

INSERT INTO tiendas (id, nombre, direccion, telefono) 
VALUES (2, 'Sterenfix Plaza Galerías', 'Blvd. Manuel Ávila Camacho 200, Naucalpan, Edo. Méx.', '+502 2345 6789');

INSERT INTO tiendas (id, nombre, direccion, telefono) 
VALUES (3, 'Sterenfix Centro Sur', 'Calzada del Hueso 519, Coyoacán, CDMX', '+502 2456 7890');

INSERT INTO tiendas (id, nombre, direccion, telefono) 
VALUES (4, 'Sterenfix Parque Tecnológico', 'Av. Universidad Tecnológica 401, Querétaro, Qro.', '+502 7765 4321');

-- 2. Inserción de Puestos
INSERT INTO puestos (id, nombre, descripcion) 
VALUES (1, 'Gerente de Sucursal', 'Responsable de la operación integral, metas comerciales y liderazgo del personal de la sucursal.');

INSERT INTO puestos (id, nombre, descripcion) 
VALUES (2, 'Ingeniero de Soporte Técnico', 'Diagnóstico y reparación a nivel componente de equipos de cómputo, audio y gadgets.');

INSERT INTO puestos (id, nombre, descripcion) 
VALUES (3, 'Técnico Especialista en Hardware', 'Mantenimiento preventivo/correctivo, ensamble de PCs gamer y sustitución de periféricos.');

INSERT INTO puestos (id, nombre, descripcion) 
VALUES (4, 'Ejecutivo de Ventas y Atención', 'Asesoría directa al cliente, venta de componentes electrónicos y facturación.');

INSERT INTO puestos (id, nombre, descripcion) 
VALUES (5, 'Asistente Administrativo', 'Control de inventario, recepción de garantías y gestión de expedientes laborales.');

-- 3. Inserción de Empleados
-- Empleado 1 (Gerente Matriz)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (1, 'Carlos Alberto', 'Mendoza Rivera', '1988-04-15', 'emp1.jpg', 45000.00, 1, 1);

-- Empleado 2 (Ingeniero Soporte Matriz)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (2, 'Valeria Sofía', 'Hernández Gómez', '1994-08-22', 'emp2.jpg', 28500.00, 2, 1);

-- Empleado 3 (Ventas Matriz)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (3, 'Miguel Ángel', 'Torres Castillo', '1997-11-03', 'emp3.jpg', 16200.00, 4, 1);

-- Empleado 4 (Gerente Plaza Galerías)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (4, 'Adriana Marcela', 'Ríos Domínguez', '1985-02-18', 'emp4.jpg', 42000.00, 1, 2);

-- Empleado 5 (Técnico Hardware Plaza Galerías)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (5, 'Fernando Javier', 'Ortega Silva', '1996-06-30', 'emp5.jpg', 23400.00, 3, 2);

-- Empleado 6 (Ventas Plaza Galerías)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (6, 'Paola Ximena', 'Morales Vázquez', '1999-09-12', 'emp6.jpg', 15800.00, 4, 2);

-- Empleado 7 (Ingeniero Soporte Centro Sur)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (7, 'Ricardo Daniel', 'Pérez Navarro', '1992-01-25', 'emp7.jpg', 31000.00, 2, 3);

-- Empleado 8 (Asistente Administrativo Centro Sur)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (8, 'Mariana Elizabeth', 'Gutiérrez Saldaña', '1995-12-05', 'emp8.jpg', 18900.00, 5, 3);

-- Empleado 9 (Gerente Parque Tecnológico)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (9, 'Guillermo Enrique', 'Álvarez Montiel', '1983-07-19', 'emp9.jpg', 48000.00, 1, 4);

-- Empleado 10 (Técnico Hardware Parque Tecnológico)
INSERT INTO empleados (id, nombres, apellidos, fecha_nacimiento, fotografia, salario, puesto_id, tienda_id)
VALUES (10, 'Brenda Lucía', 'Delgado Campos', '1998-03-08', 'emp10.jpg', 22800.00, 3, 4);

-- 4. Inserción de Logros (Positivos y Negativos)
INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (1, 1, 'Superó la meta de facturación mensual de la sucursal en un 18% durante el trimestre Q3.', 'positivo', '2025-09-30');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (2, 2, 'Certificación oficial Oracle Database Associate y solución récord en diagnósticos de placas madre.', 'positivo', '2025-10-14');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (3, 2, 'Falla en el seguimiento a ticket de cliente corporativo ocasionando retraso de 48 horas en entrega.', 'negativo', '2025-11-05');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (4, 3, 'Reconocimiento como Mejor Vendedor del Mes por satisfacción al cliente y venta cruzada de componentes.', 'positivo', '2025-11-30');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (5, 4, 'Implementación de protocolo de optimización de inventarios que redujo mermas en un 12%.', 'positivo', '2025-08-15');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (6, 5, 'Excelente desempeño en ensamble y overclocking en el evento tecnológico Steren Gaming Expo.', 'positivo', '2025-10-20');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (7, 6, 'Descuadre en corte de caja diario por falta de verificación de comprobantes fiscales.', 'negativo', '2025-12-02');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (8, 7, 'Diseño de banco de pruebas automatizado para fuentes de poder y tarjetas de video.', 'positivo', '2026-01-18');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (9, 9, 'Acreditación de la tienda bajo estándares de calidad ISO 9001 en procesos de servicio técnico.', 'positivo', '2026-02-10');

INSERT INTO logros (id, empleado_id, descripcion, tipo, fecha_ocurrencia)
VALUES (10, 10, 'Resolución de 150 órdenes de servicio continuo sin ninguna garantía reincidente.', 'positivo', '2026-03-01');

-- 5. Inserción de Llamadas de Atención
INSERT INTO llamadas_atencion (id, empleado_id, descripcion, fecha_ocurrencia)
VALUES (1, 3, 'Retardo injustificado de 45 minutos en la apertura de piso de venta de la sucursal.', '2025-10-02');

INSERT INTO llamadas_atencion (id, empleado_id, descripcion, fecha_ocurrencia)
VALUES (2, 5, 'Incumplimiento de la norma de uso obligatorio de pulsera antiestática y equipo de seguridad en laboratorio.', '2025-11-12');

INSERT INTO llamadas_atencion (id, empleado_id, descripcion, fecha_ocurrencia)
VALUES (3, 6, 'Uso inapropiado de dispositivo telefónico personal en mostrador durante la atención a cliente.', '2025-12-15');

INSERT INTO llamadas_atencion (id, empleado_id, descripcion, fecha_ocurrencia)
VALUES (4, 10, 'Omisión en el registro de salida de refacción en el sistema interno de inventarios.', '2026-01-22');

COMMIT;
