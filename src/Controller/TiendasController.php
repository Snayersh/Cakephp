<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador del Catálogo de Tiendas
 */
class TiendasController extends AppController
{
    public function index()
    {
        $tiendas = $this->Tiendas->find()
            ->contain(['Empleados'])
            ->order(['Tiendas.nombre' => 'ASC'])
            ->all();

        $this->set(compact('tiendas'));
    }

    public function view($id = null)
    {
        $tienda = $this->Tiendas->get($id, [
            'contain' => [
                'Empleados' => ['Puestos'],
            ],
        ]);

        $subtotalNomina = 0;
        foreach ($tienda->empleados as $emp) {
            $subtotalNomina += (float)$emp->salario;
        }

        $this->set(compact('tienda', 'subtotalNomina'));
    }

    public function add()
    {
        $tienda = $this->Tiendas->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $tienda = $this->Tiendas->patchEntity($tienda, $this->getRequest()->getData());
            if ($this->Tiendas->save($tienda)) {
                $this->Flash->success(__('La tienda "{0}" fue registrada exitosamente.', $tienda->nombre));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo registrar la tienda. Por favor verifique los datos ingresados.'));
        }
        $this->set(compact('tienda'));
    }

    public function edit($id = null)
    {
        $tienda = $this->Tiendas->get($id);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $tienda = $this->Tiendas->patchEntity($tienda, $this->getRequest()->getData());
            if ($this->Tiendas->save($tienda)) {
                $this->Flash->success(__('La tienda "{0}" fue actualizada correctamente.', $tienda->nombre));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo actualizar la información de la tienda.'));
        }
        $this->set(compact('tienda'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $tienda = $this->Tiendas->get($id, ['contain' => ['Empleados']]);

        if (!empty($tienda->empleados)) {
            $this->Flash->error(__('No es posible eliminar la tienda "{0}" porque tiene empleados asignados.', $tienda->nombre));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Tiendas->delete($tienda)) {
            $this->Flash->success(__('La tienda "{0}" fue eliminada del sistema.', $tienda->nombre));
        } else {
            $this->Flash->error(__('No se pudo eliminar la tienda.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
