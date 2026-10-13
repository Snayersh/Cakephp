<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador de Reconocimientos e Historial de Logros
 */
class LogrosController extends AppController
{
    public function index()
    {
        $tipo = $this->getRequest()->getQuery('tipo');
        $empleadoId = $this->getRequest()->getQuery('empleado_id');

        $query = $this->Logros->find()
            ->contain(['Empleados' => ['Tiendas', 'Puestos']])
            ->order(['Logros.fecha_ocurrencia' => 'DESC', 'Logros.id' => 'DESC']);

        if (!empty($tipo)) {
            $query->where(['Logros.tipo' => $tipo]);
        }

        if (!empty($empleadoId)) {
            $query->where(['Logros.empleado_id' => $empleadoId]);
        }

        $logros = $query->all();
        $empleadosList = $this->Logros->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('logros', 'empleadosList', 'tipo', 'empleadoId'));
    }

    public function add()
    {
        $logro = $this->Logros->newEmptyEntity();
        $targetEmpleadoId = $this->getRequest()->getQuery('empleado_id');

        if ($this->getRequest()->is('post')) {
            $logro = $this->Logros->patchEntity($logro, $this->getRequest()->getData());
            if ($this->Logros->save($logro)) {
                $this->Flash->success(__('El logro/reconocimiento fue registrado satisfactoriamente.'));
                $redirectEmpleado = $this->getRequest()->getData('redirect_empleado');
                if (!empty($redirectEmpleado)) {
                    return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $redirectEmpleado]);
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No fue posible registrar el logro. Revise los datos e intente de nuevo.'));
        }

        if (!empty($targetEmpleadoId) && empty($logro->empleado_id)) {
            $logro->empleado_id = (int)$targetEmpleadoId;
        }

        $empleados = $this->Logros->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('logro', 'empleados', 'targetEmpleadoId'));
    }

    public function edit($id = null)
    {
        $logro = $this->Logros->get($id, ['contain' => ['Empleados']]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $logro = $this->Logros->patchEntity($logro, $this->getRequest()->getData());
            if ($this->Logros->save($logro)) {
                $this->Flash->success(__('El registro de logro fue actualizado exitosamente.'));
                return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $logro->empleado_id]);
            }
            $this->Flash->error(__('No se pudo actualizar el registro del logro.'));
        }

        $empleados = $this->Logros->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('logro', 'empleados'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $logro = $this->Logros->get($id);
        $empId = $logro->empleado_id;

        if ($this->Logros->delete($logro)) {
            $this->Flash->success(__('El logro fue eliminado del historial.'));
        } else {
            $this->Flash->error(__('No se pudo eliminar el registro.'));
        }

        $fromProfile = $this->getRequest()->getQuery('from_profile');
        if ($fromProfile) {
            return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $empId]);
        }

        return $this->redirect(['action' => 'index']);
    }
}
