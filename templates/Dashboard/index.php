<?php
/**
 * @var \Cake\View\View $this
 * @var int $totalTiendas
 * @var int $totalPuestos
 * @var int $totalEmpleados
 * @var float $totalNomina
 * @var float $promedioSalario
 * @var int $totalLogros
 * @var int $totalLogrosPositivos
 * @var int $totalLogrosNegativos
 * @var int $totalLlamadas
 * @var iterable $ultimosEmpleados
 * @var iterable $ultimosLogros
 * @var iterable $ultimasLlamadas
 * @var array $chartTiendas
 */
$this->assign('title', 'Tablero Ejecutivo');
?>

<!-- Encabezado de Bienvenida y Métricas Generales -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold tracking-tight text-dark mb-1">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Panel de Control Corporativo
        </h2>
        <p class="text-muted fs-sm mb-0">
            Resumen en tiempo real del personal tecnológico, distribución salarial y disciplina en Sterenfix.
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build('/reportes/empleados') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Reporte General
        </a>
        <a href="<?= $this->Url->build('/empleados/add') ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Registrar Empleado
        </a>
    </div>
</div>

<!-- 4 Tarjetas de Métricas Principales (KPIs) -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat card-stat-primary h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold text-uppercase">Total Empleados</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalEmpleados) ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <i class="bi bi-shop text-primary me-1"></i><?= $totalTiendas ?> Tiendas activas
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat card-stat-success h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold text-uppercase">Nómina Total (Salarios)</span>
                    <h3 class="fw-bold mb-0 text-success">$<?= number_format($totalNomina, 2) ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <i class="bi bi-calculator me-1"></i>Promedio: $<?= number_format($promedioSalario, 2) ?>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat card-stat-info h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold text-uppercase">Logros y Méritos</span>
                    <h3 class="fw-bold mb-0 text-info"><?= number_format($totalLogros) ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <span class="text-success fw-medium"><i class="bi bi-arrow-up-circle me-1"></i><?= $totalLogrosPositivos ?> pos.</span> / 
                        <span class="text-danger fw-medium"><?= $totalLogrosNegativos ?> neg.</span>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                    <i class="bi bi-award-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-stat card-stat-warning h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold text-uppercase">Llamadas de Atención</span>
                    <h3 class="fw-bold mb-0 text-warning"><?= number_format($totalLlamadas) ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <i class="bi bi-shield-exclamation text-warning me-1"></i>Faltas registradas
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Gráficos y Accesos Rápidos -->
<div class="row g-4 mb-4">
    <!-- Gráfico Analítico de Nómina por Tienda -->
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                        <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Distribución Salarial por Sucursal
                    </h5>
                    <span class="fs-xs text-muted">Comparativa del presupuesto de nómina por tienda Sterenfix</span>
                </div>
                <a href="<?= $this->Url->build('/reportes/salarios-tienda') ?>" class="btn btn-sm btn-link text-decoration-none">Ver Detalle &rarr;</a>
            </div>
            <div class="card-body px-4 pb-4">
                <canvas id="nominaTiendaChart" style="max-height: 270px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas y Catálogos -->
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Módulos Operativos
                </h5>
            </div>
            <div class="card-body px-4 pt-1">
                <div class="list-group list-group-flush">
                    <a href="<?= $this->Url->build('/empleados') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-3 text-primary">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold fs-sm">Padrón de Empleados</h6>
                                <span class="fs-xs text-muted">Listado completo y perfiles 360°</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted fs-xs"></i>
                    </a>

                    <a href="<?= $this->Url->build('/reportes/salarios-tienda') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-success bg-opacity-10 p-2 me-3 text-success">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold fs-sm">Salarios por Tienda</h6>
                                <span class="fs-xs text-muted">Subtotales y orden descendente</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted fs-xs"></i>
                    </a>

                    <a href="<?= $this->Url->build('/tiendas') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-info bg-opacity-10 p-2 me-3 text-info">
                                <i class="bi bi-shop"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold fs-sm">Catálogo de Tiendas</h6>
                                <span class="fs-xs text-muted">Sucursales y teléfonos</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted fs-xs"></i>
                    </a>

                    <a href="<?= $this->Url->build('/puestos') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-secondary bg-opacity-10 p-2 me-3 text-secondary">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold fs-sm">Catálogo de Puestos</h6>
                                <span class="fs-xs text-muted">Perfiles y descripciones</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted fs-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Listado Reciente: Empleados, Logros y Llamadas de Atención -->
<div class="row g-4">
    <!-- Empleados Recientes con Visualizador de Fotografía -->
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-people text-primary me-2"></i>Últimos Empleados Registrados
                </h5>
                <a href="<?= $this->Url->build('/empleados') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fs-xs">Ver Todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Empleado</th>
                            <th>Puesto</th>
                            <th>Tienda</th>
                            <th class="text-end pe-4">Salario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ultimosEmpleados)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No se registran empleados aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ultimosEmpleados as $emp): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= h($emp->fotografia_url) ?>" 
                                                 alt="<?= h($emp->nombre_completo) ?>" 
                                                 class="avatar-thumbnail me-2" 
                                                 data-full-src="<?= h($emp->fotografia_url) ?>" 
                                                 data-caption="<?= h($emp->nombre_completo) ?> - <?= h($emp->puesto->nombre ?? '') ?>" 
                                                 title="Clic para ampliar fotografía">
                                            <div>
                                                <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $emp->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                    <?= h($emp->nombre_completo) ?>
                                                </a>
                                                <div class="fs-xs text-muted"><?= $emp->edad ?> años</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border"><?= h($emp->puesto->nombre ?? 'N/A') ?></span></td>
                                    <td><span class="fs-xs text-dark"><?= h($emp->tienda->nombre ?? 'N/A') ?></span></td>
                                    <td class="text-end pe-4 fw-bold text-success"><?= $emp->salario_formateado ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actividad Disciplinaria y Logros Recientes -->
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-clock-history text-info me-2"></i>Bitácora Reciente de Incidencias y Méritos
                </h5>
                <div class="d-flex gap-1">
                    <a href="<?= $this->Url->build('/logros') ?>" class="btn btn-sm btn-outline-success rounded-pill px-2 fs-xs">Logros</a>
                    <a href="<?= $this->Url->build('/llamadas-atencion') ?>" class="btn btn-sm btn-outline-warning rounded-pill px-2 fs-xs">Llamadas</a>
                </div>
            </div>
            <div class="card-body px-4 pt-1">
                <ul class="nav nav-pills mb-3 nav-fill bg-light p-1 rounded-pill" id="recentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fs-xs fw-semibold py-1" id="tab-logros" data-bs-toggle="pill" data-bs-target="#content-logros" type="button" role="tab">
                            <i class="bi bi-award me-1"></i>Últimos Logros
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fs-xs fw-semibold py-1" id="tab-llamadas" data-bs-toggle="pill" data-bs-target="#content-llamadas" type="button" role="tab">
                            <i class="bi bi-shield-exclamation me-1"></i>Últimas Llamadas
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="recentTabsContent">
                    <!-- Pestaña Logros -->
                    <div class="tab-pane fade show active" id="content-logros" role="tabpanel">
                        <?php if (empty($ultimosLogros)): ?>
                            <p class="text-center text-muted py-3">No hay registros de logros recientes.</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($ultimosLogros as $logro): ?>
                                    <div class="list-group-item px-0 py-2 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $logro->empleado_id]) ?>" class="fw-semibold text-decoration-none fs-xs text-dark">
                                                <?= h($logro->empleado->nombre_completo ?? 'Empleado') ?>
                                            </a>
                                            <span class="badge <?= $logro->isPositivo() ? 'badge-soft-success' : 'badge-soft-danger' ?> fs-xxs">
                                                <?= $logro->isPositivo() ? '<i class="bi bi-check-circle me-1"></i>Positivo' : '<i class="bi bi-exclamation-triangle me-1"></i>Negativo' ?>
                                            </span>
                                        </div>
                                        <p class="fs-xs text-muted mb-1 text-truncate"><?= h($logro->descripcion) ?></p>
                                        <span class="fs-xxs text-secondary"><i class="bi bi-calendar3 me-1"></i><?= $logro->fecha_ocurrencia ? $logro->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : '' ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pestaña Llamadas de Atención -->
                    <div class="tab-pane fade" id="content-llamadas" role="tabpanel">
                        <?php if (empty($ultimasLlamadas)): ?>
                            <p class="text-center text-muted py-3">No hay llamadas de atención recientes registradas.</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($ultimasLlamadas as $llamada): ?>
                                    <div class="list-group-item px-0 py-2 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]) ?>" class="fw-semibold text-decoration-none fs-xs text-dark">
                                                <?= h($llamada->empleado->nombre_completo ?? 'Empleado') ?>
                                            </a>
                                            <span class="badge badge-soft-warning fs-xxs">
                                                <i class="bi bi-exclamation-octagon me-1"></i>Llamada de Atención
                                            </span>
                                        </div>
                                        <p class="fs-xs text-muted mb-1 text-truncate"><?= h($llamada->descripcion) ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-xxs text-secondary"><i class="bi bi-calendar3 me-1"></i><?= $llamada->fecha_ocurrencia ? $llamada->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : '' ?></span>
                                            <span class="fs-xxs text-muted"><?= h($llamada->empleado->tienda->nombre ?? '') ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script del Gráfico Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('nominaTiendaChart');
    if (ctx) {
        const labels = <?= json_encode(array_column($chartTiendas, 'nombre')) ?>;
        const dataNomina = <?= json_encode(array_column($chartTiendas, 'nomina')) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Presupuesto de Nómina ($)',
                    data: dataNomina,
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(14, 165, 233, 0.85)',
                        'rgba(99, 102, 241, 0.85)'
                    ],
                    borderColor: [
                        '#2563eb',
                        '#10b981',
                        '#0ea5e9',
                        '#6366f1'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Nómina: $' + Number(context.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + Number(value).toLocaleString();
                            }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>
