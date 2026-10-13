-- ============================================================================
-- SCRIPT DE INICIALIZACIÓN AUTOMÁTICA EN CONTENEDOR ORACLE (FREE 23c / 19c)
-- Montado en /docker-entrypoint-initdb.d/setup/01_init.sql
-- ============================================================================

ALTER SESSION SET CONTAINER = FREEPDB1;

-- Crear o resetear usuario de la aplicación sterenfix
BEGIN
    EXECUTE IMMEDIATE 'CREATE USER sterenfix IDENTIFIED BY "SterenfixPassword123#"';
EXCEPTION
    WHEN OTHERS THEN
        EXECUTE IMMEDIATE 'ALTER USER sterenfix IDENTIFIED BY "SterenfixPassword123#" ACCOUNT UNLOCK';
END;
/

GRANT CONNECT, RESOURCE, DBA TO sterenfix;
GRANT CREATE SESSION, CREATE TABLE, CREATE VIEW, CREATE SEQUENCE, CREATE PROCEDURE, CREATE TRIGGER TO sterenfix;
GRANT UNLIMITED TABLESPACE TO sterenfix;

-- Cambiar al esquema sterenfix
ALTER SESSION SET CURRENT_SCHEMA = sterenfix;

-- Ejecución de DDL y DML
@@/docker-entrypoint-initdb.d/setup/schema.sql;
@@/docker-entrypoint-initdb.d/setup/seeds.sql;

COMMIT;
