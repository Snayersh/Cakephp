<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Controlador del Tablero de Control Principal (Dashboard)
 */
class DashboardController extends AppController
{
    public function index()
    {
        $tiendasTable = $this->fetchTable('Tiendas');
        $puestosTable = $this->fetchTable('Puestos');
        $empleadosTable = $this->fetchTable('Empleados');
        $logrosTable = $this->fetchTable('Logros');
        $llamadasTable = $this->fetchTable('LlamadasAtencion');

        // Métricas Cuantitativas
        $totalTiendas = $tiendasTable->find()->count();
        $totalPuestos = $puestosTable->find()->count();
        $totalEmpleados = $empleadosTable->find()->count();

        // Totalización de la nómina / salarios de la empresa
        $queryNomina = $empleadosTable->find();
        $queryNomina->select(['total' => $queryNomina->func()->sum('salario')]);
        $resNomina = $queryNomina->first();
        $totalNomina = (float)($resNomina->total ?? 0);

        $promedioSalario = $totalEmpleados > 0 ? ($totalNomina / $totalEmpleados) : 0;

        // Métricas de Reconocimientos y Disciplina
        $totalLogrosPositivos = $logrosTable->find()->where(['tipo' => 'positivo'])->count();
        $totalLogrosNegativos = $logrosTable->find()->where(['tipo' => 'negativo'])->count();
        $totalLogros = $totalLogrosPositivos + $totalLogrosNegativos;
        $totalLlamadas = $llamadasTable->find()->count();

        // Últimos 5 empleados registrados
        $ultimosEmpleados = $empleadosTable->find()
            ->contain(['Puestos', 'Tiendas'])
            ->order(['Empleados.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Últimos 5 logros registrados
        $ultimosLogros = $logrosTable->find()
            ->contain(['Empleados'])
            ->order(['Logros.fecha_ocurrencia' => 'DESC', 'Logros.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Últimas 5 llamadas de atención
        $ultimasLlamadas = $llamadasTable->find()
            ->contain(['Empleados' => ['Tiendas']])
            ->order(['LlamadasAtencion.fecha_ocurrencia' => 'DESC', 'LlamadasAtencion.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Distribución de salarios por tienda para gráficos analíticos
        $tiendasData = $tiendasTable->find()
            ->contain(['Empleados'])
            ->all();

        $chartTiendas = [];
        foreach ($tiendasData as $tienda) {
            $subtotal = 0;
            $count = count($tienda->empleados ?? []);
            foreach ($tienda->empleados as $emp) {
                $subtotal += (float)$emp->salario;
            }
            $chartTiendas[] = [
                'nombre' => $tienda->nombre,
                'empleados' => $count,
                'nomina' => $subtotal,
            ];
        }

        $this->set(compact(
            'totalTiendas',
            'totalPuestos',
            'totalEmpleados',
            'totalNomina',
            'promedioSalario',
            'totalLogros',
            'totalLogrosPositivos',
            'totalLogrosNegativos',
            'totalLlamadas',
            'ultimosEmpleados',
            'ultimosLogros',
            'ultimasLlamadas',
            'chartTiendas'
        ));
    }
}
