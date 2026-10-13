<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador del Catálogo de Puestos
 */
class PuestosController extends AppController
{
    public function index()
    {
        $puestos = $this->Puestos->find()
            ->contain(['Empleados'])
            ->order(['Puestos.nombre' => 'ASC'])
            ->all();

        $this->set(compact('puestos'));
    }

    public function view($id = null)
    {
        $puesto = $this->Puestos->get($id, [
            'contain' => [
                'Empleados' => ['Tiendas'],
            ],
        ]);

        $this->set(compact('puesto'));
    }

    public function add()
    {
        $puesto = $this->Puestos->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $puesto = $this->Puestos->patchEntity($puesto, $this->getRequest()->getData());
            if ($this->Puestos->save($puesto)) {
                $this->Flash->success(__('El puesto "{0}" fue registrado exitosamente.', $puesto->nombre));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo registrar el puesto. Por favor revise las validaciones.'));
        }
        $this->set(compact('puesto'));
    }

    public function edit($id = null)
    {
        $puesto = $this->Puestos->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $puesto = $this->Puestos->patchEntity($puesto, $this->getRequest()->getData());
            if ($this->Puestos->save($puesto)) {
                $this->Flash->success(__('El puesto "{0}" fue actualizado correctamente.', $puesto->nombre));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo actualizar la información del puesto.'));
        }
        $this->set(compact('puesto'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $puesto = $this->Puestos->get($id, ['contain' => ['Empleados']]);

        if (!empty($puesto->empleados)) {
            $this->Flash->error(__('No es posible eliminar el puesto "{0}" porque tiene empleados asignados.', $puesto->nombre));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Puestos->delete($puesto)) {
            $this->Flash->success(__('El puesto "{0}" fue eliminado del catálogo.', $puesto->nombre));
        } else {
            $this->Flash->error(__('No se pudo eliminar el puesto.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
