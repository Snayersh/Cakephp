<?php
/**
 * Centro de Informes y Reportes Ejecutivos
 * @var \Cake\View\View $this
 * @var int $totalEmpleados
 * @var int $totalTiendas
 * @var float $totalNomina
 * @var int $totalLogros
 * @var int $totalLlamadas
 */
$this->assign('title', 'Centro de Informes y Reportes');
?>

<div class="mb-4">
    <h2 class="h3 fw-bold text-dark mb-1">
        <i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>Centro de Informes y Reportes Sterenfix
    </h2>
    <p class="text-muted fs-sm mb-0">Generación de análisis estadísticos, nómina salarial, kárdex y seguimiento disciplinario.</p>
</div>

<div class="row g-4">
    <!-- Reporte 1: General de Empleados -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body p-4 d-flex flex-column">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary mb-3">
                    <i class="bi bi-file-earmark-person-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark fs-6 mb-2">Reporte General de Empleados</h5>
                <p class="text-muted fs-xs flex-grow-1">
                    Padrón consolidado con fotografías en miniatura, datos de adscripción, fecha de nacimiento y <strong>totalización general de salarios</strong>.
                </p>
                <a href="<?= $this->Url->build(['action' => 'empleados']) ?>" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-2">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Reporte 2: Salarios por Tienda -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body p-4 d-flex flex-column">
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success mb-3">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark fs-6 mb-2">Salarios por Tienda</h5>
                <p class="text-muted fs-xs flex-grow-1">
                    Agrupación organizada por sucursal, orden descendente por salario de mayor a menor y <strong>subtotales de nómina por tienda</strong>.
                </p>
                <a href="<?= $this->Url->build(['action' => 'salariosTienda']) ?>" class="btn btn-outline-success btn-sm rounded-pill w-100 mt-2">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Reporte 3: Logros y Reconocimientos -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body p-4 d-flex flex-column">
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info mb-3">
                    <i class="bi bi-award-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark fs-6 mb-2">Reporte de Logros</h5>
                <p class="text-muted fs-xs flex-grow-1">
                    Bitácora filtrable por empleado, rango de fechas y clasificación positiva o negativa con insignias visuales.
                </p>
                <a href="<?= $this->Url->build(['action' => 'logros']) ?>" class="btn btn-outline-info btn-sm rounded-pill w-100 mt-2">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Reporte 4: Llamadas de Atención -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body p-4 d-flex flex-column">
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning mb-3">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark fs-6 mb-2">Reporte Disciplinario</h5>
                <p class="text-muted fs-xs flex-grow-1">
                    Listado cronológico de faltas y amonestaciones registradas, agrupadas y filtrables por empleado y sucursal.
                </p>
                <a href="<?= $this->Url->build(['action' => 'llamadasAtencion']) ?>" class="btn btn-outline-warning btn-sm rounded-pill w-100 mt-2 text-dark">
                    <i class="bi bi-arrow-right me-1"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>
</div>
