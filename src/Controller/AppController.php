<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Controlador Base de la Aplicación Sterenfix
 */
class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        // Compartir el nombre del controlador y acción activos para el menú de navegación
        $this->set('currentController', $this->getName());
        $this->set('currentAction', $this->getRequest()->getParam('action'));
    }
}
