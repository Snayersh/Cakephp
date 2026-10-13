<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 */

// Usar el separador de directorios del sistema
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Raíz del proyecto
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Directorio de la aplicación (src)
if (!defined('APP_DIR')) {
    define('APP_DIR', 'src');
}

// Ruta completa a src/
if (!defined('APP')) {
    define('APP', ROOT . DS . APP_DIR . DS);
}

// Ruta de configuración
if (!defined('CONFIG')) {
    define('CONFIG', ROOT . DS . 'config' . DS);
}

// Ruta web pública
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', ROOT . DS . 'webroot' . DS);
}

// Ruta de pruebas
if (!defined('TESTS')) {
    define('TESTS', ROOT . DS . 'tests' . DS);
}

// Ruta de temporales y caché
if (!defined('TMP')) {
    define('TMP', ROOT . DS . 'tmp' . DS);
}

// Ruta de logs
if (!defined('LOGS')) {
    define('LOGS', ROOT . DS . 'logs' . DS);
}

// Ruta de caché
if (!defined('CACHE')) {
    define('CACHE', TMP . 'cache' . DS);
}

// Ruta de recursos
if (!defined('RESOURCES')) {
    define('RESOURCES', ROOT . DS . 'resources' . DS);
}
