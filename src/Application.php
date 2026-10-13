<?php
/**
 * Clase Application Principal de Sterenfix - CakePHP
 */
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    /**
     * Inicialización del entorno de la aplicación
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        // Cargar plugin del driver de Oracle si está instalado
        if (class_exists(\CakeDC\OracleDriver\Plugin::class)) {
            $this->addPlugin(\CakeDC\OracleDriver\Plugin::class);
        }
    }

    /**
     * Configuración del pipeline de middlewares HTTP
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Manejador centralizado de errores
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Manejo de assets en desarrollo
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Despachador de rutas
            ->add(new RoutingMiddleware($this))

            // Parseo de cuerpos de peticiones JSON y formularias
            ->add(new BodyParserMiddleware());

        return $middlewareQueue;
    }

    /**
     * Registro de servicios para el Contenedor de Inyección de Dependencias
     */
    public function services(ContainerInterface $container): void
    {
    }
}
