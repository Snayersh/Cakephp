<?php
/**
 * Punto de entrada HTTP para la aplicación Sterenfix - CakePHP
 */
declare(strict_types=1);

// Verificar autoloading
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo "<h1>Error del Sistema</h1><p>Las dependencias de Composer no han sido instaladas aún. Ejecute <code>composer install</code> o inicie el contenedor de Docker.</p>";
    exit(1);
}

require $autoload;

use App\Application;
use Cake\Http\Server;

// Ejecución del servidor HTTP de CakePHP
$server = new Server(new Application(dirname(__DIR__) . '/config'));
$server->emit($server->run());
