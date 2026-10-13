<?php
/**
 * Reporte de Salarios de Empleados por Tienda
 * Agrupación visual, ordenamiento descendente por salario y subtotales por tienda
 * @var \Cake\View\View $this
 * @var array $tiendasReporte
 * @var float $granTotalSalarios
 * @var int $totalEmpleadosGlobal
 */
$this->assign('title', 'Reporte de Salarios por Tienda');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-cash-stack text-success me-2"></i>Reporte de Salarios de Empleados por Tienda
        </h2>
        <p class="text-muted fs-sm mb-0">
            Distribución jerárquica de salarios ordenada estrictamente de <strong>mayor a menor</strong> dentro de cada sucursal con subtotales presupuestales.
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2 no-print">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-printer me-1"></i> Imprimir Reporte
        </button>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Regresar
        </a>
    </div>
</div>

<!-- Tarjeta de Resumen Global Consolidado -->
<div class="card p-4 border-0 shadow-sm mb-4 text-white" style="background: linear-gradient(135deg, #065f46 0%, #059669 100%);">
    <div class="row align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <span class="fs-xs text-uppercase opacity-75 fw-semibold d-block">Presupuesto Consolidado de Nómina</span>
            <h1 class="display-6 fw-bold mb-0 text-white">$<?= number_format($granTotalSalarios, 2) ?> <span class="fs-6 opacity-75">MXN</span></h1>
            <div class="fs-xs opacity-75 mt-1">Suma acumulada de todas las tiendas de la cadena Sterenfix</div>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="d-inline-flex gap-4">
                <div>
                    <span class="fs-xs text-uppercase opacity-75 d-block">Sucursales Activas</span>
                    <span class="fs-4 fw-bold"><?= count($tiendasReporte) ?></span>
                </div>
                <div class="vr opacity-50"></div>
                <div>
                    <span class="fs-xs text-uppercase opacity-75 d-block">Colaboradores Totales</span>
                    <span class="fs-4 fw-bold"><?= $totalEmpleadosGlobal ?></span>
                </div>
                <div class="vr opacity-50"></div>
                <div>
                    <span class="fs-xs text-uppercase opacity-75 d-block">Salario Promedio Global</span>
                    <span class="fs-4 fw-bold">
                        $<?= number_format($totalEmpleadosGlobal > 0 ? ($granTotalSalarios / $totalEmpleadosGlobal) : 0, 2) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agrupación Visual Organizada por Tienda -->
<?php foreach ($tiendasReporte as $idx => $grupo): ?>
    <?php
        $tienda = $grupo['tienda'];
        $empleados = $grupo['empleados'];
        $subtotal = $grupo['subtotal'];
        $promedio = $grupo['promedio'];
        $cantidad = $grupo['cantidad'];
        $porcentajeGlobal = $granTotalSalarios > 0 ? (($subtotal / $granTotalSalarios) * 100) : 0;
    ?>
    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        <!-- Cabecera de la Tienda -->
        <div class="card-header bg-light border-bottom p-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2 px-2 py-1">Tienda #<?= $tienda->id ?></span>
                    <h5 class="fw-bold text-dark mb-0 fs-6"><?= h($tienda->nombre) ?></h5>
                </div>
                <div class="fs-xs text-muted mt-1">
                    <i class="bi bi-geo-alt me-1"></i><?= h($tienda->direccion ?: 'Sin dirección') ?> | 
                    <i class="bi bi-telephone me-1"></i><?= h($tienda->telefono ?: 'N/A') ?>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex align-items-center gap-3">
                <span class="badge bg-white text-secondary border px-3 py-2 fs-xs">
                    <i class="bi bi-people me-1"></i><?= $cantidad ?> empleados
                </span>
                <span class="badge bg-white text-success border border-success-subtle px-3 py-2 fs-xs fw-bold">
                    <i class="bi bi-cash me-1"></i>Subtotal: $<?= number_format($subtotal, 2) ?>
                </span>
                <span class="badge bg-success-subtle text-success border border-success px-2 py-1 fs-xxs">
                    <?= number_format($porcentajeGlobal, 1) ?>% del total
                </span>
            </div>
        </div>

        <!-- Tabla de Empleados de la Tienda Ordenados Estrictamente DESC por Salario -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th style="width: 60px;">Foto</th>
                            <th>Empleado</th>
                            <th>Puesto</th>
                            <th>Edad</th>
                            <th class="text-end pe-4">Salario (Orden Mayor a Menor)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($empleados)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">No hay empleados asignados a esta sucursal.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($empleados as $pos => $emp): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?= $pos + 1 ?>°</td>
                                    <td>
                                        <img src="<?= h($emp->fotografia_url) ?>" 
                                             alt="<?= h($emp->nombre_completo) ?>" 
                                             class="avatar-thumbnail" 
                                             data-full-src="<?= h($emp->fotografia_url) ?>" 
                                             data-caption="<?= h($emp->nombre_completo) ?> &bull; <?= h($emp->puesto->nombre ?? '') ?>"
                                             title="Clic para ampliar imagen">
                                    </td>
                                    <td>
                                        <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $emp->id]) ?>" class="fw-bold text-dark text-decoration-none">
                                            <?= h($emp->nombre_completo) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border"><?= h($emp->puesto->nombre ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <span class="fs-xs text-muted"><?= $emp->edad ?> años</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success fs-6">
                                        <?= $emp->salario_formateado ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <!-- Fila de Subtotal por Tienda Destacada -->
                    <tfoot class="table-success bg-opacity-50 border-top border-2 border-success">
                        <tr class="fw-bold">
                            <td colspan="5" class="ps-4 text-uppercase fs-xs text-success py-2">
                                <i class="bi bi-calculator-fill me-1"></i> Subtotal de Salarios Sucursal (<?= h($tienda->nombre) ?>):
                            </td>
                            <td class="text-end pe-4 text-success fs-6 py-2">
                                $<?= number_format($subtotal, 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>
