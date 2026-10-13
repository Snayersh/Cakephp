<?php
/**
 * Definición de Rutas de la Aplicación Sterenfix
 */
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        // Redirigir la raíz al Dashboard
        $builder->connect('/', ['controller' => 'Dashboard', 'action' => 'index']);
        $builder->connect('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

        // Rutas directas para Reportes
        $builder->connect('/reportes', ['controller' => 'Reportes', 'action' => 'index']);
        $builder->connect('/reportes/empleados', ['controller' => 'Reportes', 'action' => 'empleados']);
        $builder->connect('/reportes/salarios-tienda', ['controller' => 'Reportes', 'action' => 'salariosTienda']);
        $builder->connect('/reportes/logros', ['controller' => 'Reportes', 'action' => 'logros']);
        $builder->connect('/reportes/llamadas-atencion', ['controller' => 'Reportes', 'action' => 'llamadasAtencion']);

        // Rutas con guiones para Llamadas de Atención
        $builder->connect('/llamadas-atencion', ['controller' => 'LlamadasAtencion', 'action' => 'index']);
        $builder->connect('/llamadas-atencion/add', ['controller' => 'LlamadasAtencion', 'action' => 'add']);
        $builder->connect('/llamadas-atencion/edit/*', ['controller' => 'LlamadasAtencion', 'action' => 'edit']);
        $builder->connect('/llamadas-atencion/delete/*', ['controller' => 'LlamadasAtencion', 'action' => 'delete']);
        $builder->connect('/llamadas-atencion/view/*', ['controller' => 'LlamadasAtencion', 'action' => 'view']);

        // Fallback para controladores restantes (Tiendas, Puestos, Empleados, Logros, etc.)
        $builder->fallbacks();
    });
};
