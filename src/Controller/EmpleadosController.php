<?php
declare(strict_types=1);

namespace App\Controller;

use Laminas\Diactoros\UploadedFile;

/**
 * Controlador de Gestión de Empleados
 */
class EmpleadosController extends AppController
{
    public function index()
    {
        $search = $this->getRequest()->getQuery('search');
        $tiendaId = $this->getRequest()->getQuery('tienda_id');
        $puestoId = $this->getRequest()->getQuery('puesto_id');

        $query = $this->Empleados->find()
            ->contain(['Puestos', 'Tiendas', 'Logros', 'LlamadasAtencion'])
            ->order(['Empleados.nombres' => 'ASC', 'Empleados.apellidos' => 'ASC']);

        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Empleados.nombres LIKE' => '%' . $search . '%',
                    'Empleados.apellidos LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        if (!empty($tiendaId)) {
            $query->where(['Empleados.tienda_id' => $tiendaId]);
        }

        if (!empty($puestoId)) {
            $query->where(['Empleados.puesto_id' => $puestoId]);
        }

        $empleados = $query->all();
        $tiendas = $this->Empleados->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();
        $puestos = $this->Empleados->Puestos->find('list')->order(['nombre' => 'ASC'])->all();

        $this->set(compact('empleados', 'tiendas', 'puestos', 'search', 'tiendaId', 'puestoId'));
    }

    /**
     * Perfil 360° / Kárdex Integral del Empleado
     */
    public function view($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => [
                'Puestos',
                'Tiendas',
                'Logros' => function ($q) {
                    return $q->order(['Logros.fecha_ocurrencia' => 'DESC', 'Logros.id' => 'DESC']);
                },
                'LlamadasAtencion' => function ($q) {
                    return $q->order(['LlamadasAtencion.fecha_ocurrencia' => 'DESC', 'LlamadasAtencion.id' => 'DESC']);
                },
            ],
        ]);

        $logrosPositivos = 0;
        $logrosNegativos = 0;
        foreach ($empleado->logros as $logro) {
            if ($logro->isPositivo()) {
                $logrosPositivos++;
            } else {
                $logrosNegativos++;
            }
        }
        $totalLlamadas = count($empleado->llamadas_atencion);

        $this->set(compact('empleado', 'logrosPositivos', 'logrosNegativos', 'totalLlamadas'));
    }

    public function add()
    {
        $empleado = $this->Empleados->newEmptyEntity();
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();

            // Procesar subida de archivo fotográfico
            $archivoFoto = $this->getRequest()->getData('fotografia_file');
            if ($archivoFoto instanceof UploadedFile && $archivoFoto->getError() === UPLOAD_ERR_OK) {
                $filename = $this->procesarFotografia($archivoFoto);
                if ($filename) {
                    $data['fotografia'] = $filename;
                }
            }

            $empleado = $this->Empleados->patchEntity($empleado, $data);
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('El empleado "{0}" ha sido registrado correctamente.', $empleado->nombre_completo));
                return $this->redirect(['action' => 'view', $empleado->id]);
            }
            $this->Flash->error(__('No se pudo registrar al empleado. Revise los campos marcados en rojo.'));
        }

        $puestos = $this->Empleados->Puestos->find('list')->order(['nombre' => 'ASC'])->all();
        $tiendas = $this->Empleados->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();
        $this->set(compact('empleado', 'puestos', 'tiendas'));
    }

    public function edit($id = null)
    {
        $empleado = $this->Empleados->get($id, [
            'contain' => ['Puestos', 'Tiendas'],
        ]);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $data = $this->getRequest()->getData();
            $fotoAnterior = $empleado->fotografia;

            // Procesar nueva fotografía si fue cargada
            $archivoFoto = $this->getRequest()->getData('fotografia_file');
            if ($archivoFoto instanceof UploadedFile && $archivoFoto->getError() === UPLOAD_ERR_OK) {
                $filename = $this->procesarFotografia($archivoFoto);
                if ($filename) {
                    $data['fotografia'] = $filename;
                    // Eliminar foto anterior si no es de los seeds precargados
                    if (!empty($fotoAnterior) && !preg_match('/^emp\d+\.jpg$/', $fotoAnterior)) {
                        $antiguoPath = WWW_ROOT . 'img' . DS . 'empleados' . DS . $fotoAnterior;
                        if (file_exists($antiguoPath)) {
                            @unlink($antiguoPath);
                        }
                    }
                }
            }

            $empleado = $this->Empleados->patchEntity($empleado, $data);
            if ($this->Empleados->save($empleado)) {
                $this->Flash->success(__('El expediente del empleado "{0}" fue actualizado con éxito.', $empleado->nombre_completo));
                return $this->redirect(['action' => 'view', $empleado->id]);
            }
            $this->Flash->error(__('No se pudo guardar la información. Por favor verifique el formulario.'));
        }

        $puestos = $this->Empleados->Puestos->find('list')->order(['nombre' => 'ASC'])->all();
        $tiendas = $this->Empleados->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();
        $this->set(compact('empleado', 'puestos', 'tiendas'));
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $empleado = $this->Empleados->get($id);

        $foto = $empleado->fotografia;
        if ($this->Empleados->delete($empleado)) {
            // Eliminar imagen del sistema de archivos si corresponde
            if (!empty($foto) && !preg_match('/^emp\d+\.jpg$/', $foto)) {
                $fotoPath = WWW_ROOT . 'img' . DS . 'empleados' . DS . $foto;
                if (file_exists($fotoPath)) {
                    @unlink($fotoPath);
                }
            }
            $this->Flash->success(__('El empleado "{0}" ha sido eliminado del sistema.', $empleado->nombre_completo));
        } else {
            $this->Flash->error(__('No se pudo eliminar el empleado.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Valida y almacena de forma segura la fotografía del empleado
     */
    private function procesarFotografia(UploadedFile $file): ?string
    {
        $extension = strtolower(pathinfo($file->getClientFilename() ?? '', PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $permitidas)) {
            return null;
        }

        $dirDestino = WWW_ROOT . 'img' . DS . 'empleados';
        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }

        $nuevoNombre = 'empleado_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $rutaDestino = $dirDestino . DS . $nuevoNombre;

        $file->moveTo($rutaDestino);

        return $nuevoNombre;
    }
}
