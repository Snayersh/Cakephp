<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador de Incidencias Disciplinarias (Llamadas de Atención)
 */
class LlamadasAtencionController extends AppController
{
    public function index()
    {
        $empleadoId = $this->getRequest()->getQuery('empleado_id');

        $query = $this->LlamadasAtencion->find()
            ->contain(['Empleados' => ['Tiendas', 'Puestos']])
            ->order(['LlamadasAtencion.fecha_ocurrencia' => 'DESC', 'LlamadasAtencion.id' => 'DESC']);

        if (!empty($empleadoId)) {
            $query->where(['LlamadasAtencion.empleado_id' => $empleadoId]);
        }

        $llamadas = $query->all();
        $empleadosList = $this->LlamadasAtencion->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('llamadas', 'empleadosList', 'empleadoId'));
    }

    public function add()
    {
        $llamada = $this->LlamadasAtencion->newEmptyEntity();
        $targetEmpleadoId = $this->getRequest()->getQuery('empleado_id');

        if ($this->getRequest()->is('post')) {
            $llamada = $this->LlamadasAtencion->patchEntity($llamada, $this->getRequest()->getData());
            if ($this->LlamadasAtencion->save($llamada)) {
                $this->Flash->success(__('La llamada de atención fue registrada correctamente en el expediente.'));
                $redirectEmpleado = $this->getRequest()->getData('redirect_empleado');
                if (!empty($redirectEmpleado)) {
                    return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $redirectEmpleado]);
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No fue posible registrar la llamada de atención. Verifique los campos.'));
        }

        if (!empty($targetEmpleadoId) && empty($llamada->empleado_id)) {
            $llamada->empleado_id = (int)$targetEmpleadoId;
        }

        $empleados = $this->LlamadasAtencion->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('llamada', 'empleados', 'targetEmpleadoId'));
    }

    public function edit($id = null)
    {
        $llamada = $this->LlamadasAtencion->get($id, ['contain' => ['Empleados']]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $llamada = $this->LlamadasAtencion->patchEntity($llamada, $this->getRequest()->getData());
            if ($this->LlamadasAtencion->save($llamada)) {
                $this->Flash->success(__('La llamada de atención fue actualizada exitosamente.'));
                return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]);
            }
            $this->Flash->error(__('No se pudo actualizar el registro disciplinario.'));
        }

        $empleados = $this->LlamadasAtencion->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('llamada', 'empleados'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $llamada = $this->LlamadasAtencion->get($id);
        $empId = $llamada->empleado_id;

        if ($this->LlamadasAtencion->delete($llamada)) {
            $this->Flash->success(__('La llamada de atención fue removida del historial.'));
        } else {
            $this->Flash->error(__('No se pudo eliminar el registro disciplinario.'));
        }

        $fromProfile = $this->getRequest()->getQuery('from_profile');
        if ($fromProfile) {
            return $this->redirect(['controller' => 'Empleados', 'action' => 'view', $empId]);
        }

        return $this->redirect(['action' => 'index']);
    }
}
