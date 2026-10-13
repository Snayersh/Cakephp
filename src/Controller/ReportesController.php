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
        // Resumen general para la central de reportes
        $this->fetchTable('Empleados');
        $this->fetchTable('Tiendas');
        $this->fetchTable('Logros');
        $this->fetchTable('LlamadasAtencion');

        $totalEmpleados = $this->Empleados->find()->count();
        $totalTiendas = $this->Tiendas->find()->count();

        $queryNomina = $this->Empleados->find();
        $queryNomina->select(['total' => $queryNomina->func()->sum('salario')]);
        $totalNomina = (float)($queryNomina->first()->total ?? 0);

        $totalLogros = $this->Logros->find()->count();
        $totalLlamadas = $this->LlamadasAtencion->find()->count();

        $this->set(compact('totalEmpleados', 'totalTiendas', 'totalNomina', 'totalLogros', 'totalLlamadas'));
    }

    /**
     * Reporte 1: Reporte General de Empleados
     * Incluye totalización general de salarios
     */
    public function empleados()
    {
        $this->fetchTable('Empleados');
        $tiendaId = $this->getRequest()->getQuery('tienda_id');
        $puestoId = $this->getRequest()->getQuery('puesto_id');
        $search = $this->getRequest()->getQuery('search');

        $query = $this->Empleados->find()
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

        $tiendas = $this->Empleados->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();
        $puestos = $this->Empleados->Puestos->find('list')->order(['nombre' => 'ASC'])->all();

        $this->set(compact('empleados', 'totalSalarios', 'promedioSalario', 'tiendas', 'puestos', 'tiendaId', 'puestoId', 'search'));
    }

    /**
     * Reporte 2: Salarios de Empleados Agrupados por Tienda
     * Ordenamiento estricto por salario DESC dentro de cada tienda y subtotales por tienda
     */
    public function salariosTienda()
    {
        $this->fetchTable('Tiendas');
        $this->fetchTable('Empleados');

        $tiendas = $this->Tiendas->find()
            ->order(['Tiendas.nombre' => 'ASC'])
            ->all();

        $tiendasReporte = [];
        $granTotalSalarios = 0;
        $totalEmpleadosGlobal = 0;

        foreach ($tiendas as $tienda) {
            // Empleados ordenados estrictamente por salario DE MAYOR A MENOR dentro de cada tienda
            $empleados = $this->Empleados->find()
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
        $this->fetchTable('Logros');
        $empleadoId = $this->getRequest()->getQuery('empleado_id');
        $tipo = $this->getRequest()->getQuery('tipo');
        $fechaDesde = $this->getRequest()->getQuery('fecha_desde');
        $fechaHasta = $this->getRequest()->getQuery('fecha_hasta');

        $query = $this->Logros->find()
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

        $empleados = $this->Logros->Empleados->find('list', [
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
        $this->fetchTable('LlamadasAtencion');
        $empleadoId = $this->getRequest()->getQuery('empleado_id');
        $tiendaId = $this->getRequest()->getQuery('tienda_id');
        $fechaDesde = $this->getRequest()->getQuery('fecha_desde');
        $fechaHasta = $this->getRequest()->getQuery('fecha_hasta');

        $query = $this->LlamadasAtencion->find()
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

        $empleados = $this->LlamadasAtencion->Empleados->find('list', [
            'keyField' => 'id',
            'valueField' => function ($emp) {
                return $emp->nombre_completo;
            },
        ])->order(['nombres' => 'ASC'])->all();

        $this->fetchTable('Tiendas');
        $tiendas = $this->Tiendas->find('list')->order(['nombre' => 'ASC'])->all();

        $this->set(compact('llamadas', 'empleados', 'tiendas', 'empleadoId', 'tiendaId', 'fechaDesde', 'fechaHasta'));
    }
}
