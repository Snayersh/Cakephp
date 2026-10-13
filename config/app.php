<?php
/**
 * Configuración Principal de la Aplicación Sterenfix - CakePHP
 */

return [
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

    'App' => [
        'title' => env('APP_NAME', 'Sterenfix - Sistema de Personal'),
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'es_ES'),
        'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'America/Mexico_City'),
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [ROOT . DS . 'templates' . DS],
        ],
    ],

    'Security' => [
        'salt' => env('SECURITY_SALT', 'c30d92376e1a0df3311dbb5b991386d38e8ec8e0f6c77bb320c15e8b4e7a68fc'),
    ],

    'Asset' => [],

    'Error' => [
        'errorLevel' => E_ALL,
        'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    'Session' => [
        'defaults' => 'php',
        'timeout' => 1440,
    ],

    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => env('DB_DRIVER', 'CakeDC\OracleDriver\Database\Driver\Oracle'),
            'persistent' => false,
            'host' => env('DB_HOST', 'sterenfix_db'),
            'port' => env('DB_PORT', '1521'),
            'username' => env('DB_USERNAME', 'sterenfix'),
            'password' => env('DB_PASSWORD', 'SterenfixPassword123#'),
            'database' => env('DB_DATABASE', 'FREEPDB1'),
            'encoding' => 'AL32UTF8',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,
            'server_version' => 23,
            'autoincrement' => true,
            'quoteIdentifiers' => false,
        ],
    ],

    'Log' => [
        'debug' => [
            'className' => 'Cake\Log\Engine\FileLog',
            'path' => LOGS,
            'file' => 'debug',
            'url' => env('LOG_DEBUG_URL', null),
            'scopes' => null,
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => 'Cake\Log\Engine\FileLog',
            'path' => LOGS,
            'file' => 'error',
            'url' => env('LOG_ERROR_URL', null),
            'scopes' => null,
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],
];
