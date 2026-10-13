<?php
/**
 * Bootstrap del sistema Sterenfix - CakePHP
 */
declare(strict_types=1);

require __DIR__ . '/paths.php';
require ROOT . '/vendor/autoload.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Log\Log;
use Cake\Routing\Router;

// Cargar variables de entorno desde .env si existe y no están definidas en getenv()
$envFile = ROOT . DS . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }
            if (getenv($name) === false) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

// Configuración de zona horaria y codificación
date_default_timezone_set(Configure::read('App.defaultTimezone', 'America/Mexico_City'));
mb_internal_encoding(Configure::read('App.encoding', 'UTF-8'));
ini_set('intl.default_locale', Configure::read('App.defaultLocale', 'es_ES'));

// Manejador moderno de errores y excepciones CakePHP 4.4+
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

// Inicializar Caché
Cache::setConfig(Configure::consume('Cache'));

// Inicializar Router
Router::reload();

// Configurar conexiones de base de datos
ConnectionManager::setConfig(Configure::consume('Datasources'));
Log::setConfig(Configure::consume('Log'));
