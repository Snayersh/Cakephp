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
        $this->fetchTable('Tiendas');
        $this->fetchTable('Puestos');
        $this->fetchTable('Empleados');
        $this->fetchTable('Logros');
        $this->fetchTable('LlamadasAtencion');

        // Métricas Cuantitativas
        $totalTiendas = $this->Tiendas->find()->count();
        $totalPuestos = $this->Puestos->find()->count();
        $totalEmpleados = $this->Empleados->find()->count();

        // Totalización de la nómina / salarios de la empresa
        $queryNomina = $this->Empleados->find();
        $queryNomina->select(['total' => $queryNomina->func()->sum('salario')]);
        $resNomina = $queryNomina->first();
        $totalNomina = (float)($resNomina->total ?? 0);

        $promedioSalario = $totalEmpleados > 0 ? ($totalNomina / $totalEmpleados) : 0;

        // Métricas de Reconocimientos y Disciplina
        $totalLogrosPositivos = $this->Logros->find()->where(['tipo' => 'positivo'])->count();
        $totalLogrosNegativos = $this->Logros->find()->where(['tipo' => 'negativo'])->count();
        $totalLogros = $totalLogrosPositivos + $totalLogrosNegativos;
        $totalLlamadas = $this->LlamadasAtencion->find()->count();

        // Últimos 5 empleados registrados
        $ultimosEmpleados = $this->Empleados->find()
            ->contain(['Puestos', 'Tiendas'])
            ->order(['Empleados.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Últimos 5 logros registrados
        $ultimosLogros = $this->Logros->find()
            ->contain(['Empleados'])
            ->order(['Logros.fecha_ocurrencia' => 'DESC', 'Logros.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Últimas 5 llamadas de atención
        $ultimasLlamadas = $this->LlamadasAtencion->find()
            ->contain(['Empleados' => ['Tiendas']])
            ->order(['LlamadasAtencion.fecha_ocurrencia' => 'DESC', 'LlamadasAtencion.id' => 'DESC'])
            ->limit(5)
            ->all();

        // Distribución de salarios por tienda para gráficos analíticos
        $tiendasData = $this->Tiendas->find()
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
