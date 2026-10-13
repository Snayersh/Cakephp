<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador de Informes y Reportes Ejecutivos del Sistema
 */
class ReportesController extends AppController
{
    public function index()
    {
        $empleadosTable = $this->fetchTable('Empleados');
        $tiendasTable = $this->fetchTable('Tiendas');
        $logrosTable = $this->fetchTable('Logros');
        $llamadasTable = $this->fetchTable('LlamadasAtencion');

        $totalEmpleados = $empleadosTable->find()->count();
        $totalTiendas = $tiendasTable->find()->count();

        $queryNomina = $empleadosTable->find();
        $queryNomina->select(['total' => $queryNomina->func()->sum('salario')]);
        $totalNomina = (float)($queryNomina->first()->total ?? 0);

        $totalLogros = $logrosTable->find()->count();
        $totalLlamadas = $llamadasTable->find()->count();

        $this->set(compact('totalEmpleados', 'totalTiendas', 'totalNomina', 'totalLogros', 'totalLlamadas'));
    }

    /**
     * Reporte 1: Reporte General de Empleados
     * Incluye totalización general de salarios
     */
    public function empleados()
    {
        $empleadosTable = $this->fetchTable('Empleados');
        $tiendaId = $this->getRequest()->getQuery('tienda_id');
        $puestoId = $this->getRequest()->getQuery('puesto_id');
        $search = $this->getRequest()->getQuery('search');

        $query = $empleadosTable->find()
            ->contain(['Puestos', 'Tiendas'])
            ->order(['Empleados.nombres' => 'ASC', 'Empleados.apellidos' => 'ASC']);

        if (!empty($tiendaId)) {
            $query->where(['Empleados.tienda_id' => $tiendaId]);
        }

        if (!empty($puestoId)) {
            $query->where(['Empleados.puesto_id' => $puestoId]);
        }

        if (!empty($search)) {
            $query->where([
                'OR' => [
                    'Empleados.nombres LIKE' => '%' . $search . '%',
                    'Empleados.apellidos LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        $empleados = $query->all();

        // Calcular la totalización general de salarios
        $totalSalarios = 0;
        foreach ($empleados as $emp) {
            $totalSalarios += (float)$emp->salario;
        }

        $promedioSalario = count($empleados) > 0 ? ($totalSalarios / count($empleados)) : 0;

        $tiendas = $empleadosTable->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();
        $puestos = $empleadosTable->Puestos->find('list')->order(['nombre' => 'ASC'])->all();

        $this->set(compact('empleados', 'totalSalarios', 'promedioSalario', 'tiendas', 'puestos', 'tiendaId', 'puestoId', 'search'));
    }

    /**
     * Reporte 2: Salarios de Empleados Agrupados por Tienda
     * Ordenamiento estricto por salario DESC dentro de cada tienda y subtotales por tienda
     */
    public function salariosTienda()
    {
        $tiendasTable = $this->fetchTable('Tiendas');
        $empleadosTable = $this->fetchTable('Empleados');

        $tiendas = $tiendasTable->find()
            ->order(['Tiendas.nombre' => 'ASC'])
            ->all();

        $tiendasReporte = [];
        $granTotalSalarios = 0;
        $totalEmpleadosGlobal = 0;

        foreach ($tiendas as $tienda) {
            $empleados = $empleadosTable->find()
                ->contain(['Puestos'])
                ->where(['Empleados.tienda_id' => $tienda->id])
                ->order(['Empleados.salario' => 'DESC', 'Empleados.nombres' => 'ASC'])
                ->all();

            $subtotalTienda = 0;
            foreach ($empleados as $emp) {
                $subtotalTienda += (float)$emp->salario;
            }

            $granTotalSalarios += $subtotalTienda;
            $totalEmpleadosGlobal += count($empleados);

            $tiendasReporte[] = [
                'tienda' => $tienda,
                'empleados' => $empleados,
                'subtotal' => $subtotalTienda,
                'promedio' => count($empleados) > 0 ? ($subtotalTienda / count($empleados)) : 0,
                'cantidad' => count($empleados),
            ];
        }

        $this->set(compact('tiendasReporte', 'granTotalSalarios', 'totalEmpleadosGlobal'));
    }

    /**
     * Reporte 3: Reporte General de Logros
     * Filtrable por empleado, rango de fechas y tipo (Positivo/Negativo) con badges de color
     */
    public function logros()
    {
        $logrosTable = $this->fetchTable('Logros');
        $empleadoId = $this->getRequest()->getQuery('empleado_id');
        $tipo = $this->getRequest()->getQuery('tipo');
        $fechaDesde = $this->getRequest()->getQuery('fecha_desde');
        $fechaHasta = $this->getRequest()->getQuery('fecha_hasta');

        $query = $logrosTable->find()
            ->contain(['Empleados' => ['Tiendas', 'Puestos']])
            ->order(['Logros.fecha_ocurrencia' => 'DESC', 'Logros.id' => 'DESC']);

        if (!empty($empleadoId)) {
            $query->where(['Logros.empleado_id' => $empleadoId]);
        }

        if (!empty($tipo)) {
            $query->where(['Logros.tipo' => $tipo]);
        }

        if (!empty($fechaDesde)) {
            $query->where(['Logros.fecha_ocurrencia >=' => $fechaDesde]);
        }

        if (!empty($fechaHasta)) {
            $query->where(['Logros.fecha_ocurrencia <=' => $fechaHasta]);
        }

        $logros = $query->all();

        $conteoPositivos = 0;
        $conteoNegativos = 0;
        foreach ($logros as $logro) {
            if ($logro->isPositivo()) {
                $conteoPositivos++;
            } else {
                $conteoNegativos++;
            }
        }

        $empleados = $logrosTable->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->set(compact('logros', 'empleados', 'conteoPositivos', 'conteoNegativos', 'empleadoId', 'tipo', 'fechaDesde', 'fechaHasta'));
    }

    /**
     * Reporte 4: Reporte de Incidencias / Llamadas de Atención
     * Listado cronológico por empleado y tienda
     */
    public function llamadasAtencion()
    {
        $llamadasTable = $this->fetchTable('LlamadasAtencion');
        $tiendasTable = $this->fetchTable('Tiendas');

        $empleadoId = $this->getRequest()->getQuery('empleado_id');
        $tiendaId = $this->getRequest()->getQuery('tienda_id');
        $fechaDesde = $this->getRequest()->getQuery('fecha_desde');
        $fechaHasta = $this->getRequest()->getQuery('fecha_hasta');

        $query = $llamadasTable->find()
            ->contain(['Empleados' => ['Tiendas', 'Puestos']])
            ->order(['LlamadasAtencion.fecha_ocurrencia' => 'DESC', 'LlamadasAtencion.id' => 'DESC']);

        if (!empty($empleadoId)) {
            $query->where(['LlamadasAtencion.empleado_id' => $empleadoId]);
        }

        if (!empty($tiendaId)) {
            $query->matching('Empleados', function ($q) use ($tiendaId) {
                return $q->where(['Empleados.tienda_id' => $tiendaId]);
            });
        }

        if (!empty($fechaDesde)) {
            $query->where(['LlamadasAtencion.fecha_ocurrencia >=' => $fechaDesde]);
        }

        if (!empty($fechaHasta)) {
            $query->where(['LlamadasAtencion.fecha_ocurrencia <=' => $fechaHasta]);
        }

        $llamadas = $query->all();

        $empleados = $llamadasTable->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $tiendas = $tiendasTable->find('list')->order(['nombre' => 'ASC'])->all();

        $this->set(compact('llamadas', 'empleados', 'tiendas', 'empleadoId', 'tiendaId', 'fechaDesde', 'fechaHasta'));
    }
}
