<?php
/**
 * Reporte General de Logros con Filtros Avanzados
 * @var \Cake\View\View $this
 * @var iterable $logros
 * @var iterable $empleados
 * @var int $conteoPositivos
 * @var int $conteoNegativos
 * @var string|null $empleadoId
 * @var string|null $tipo
 * @var string|null $fechaDesde
 * @var string|null $fechaHasta
 */
$this->assign('title', 'Reporte General de Logros');
$totalConteo = $conteoPositivos + $conteoNegativos;
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-award-fill text-info me-2"></i>Reporte General de Logros y Méritos
        </h2>
        <p class="text-muted fs-sm mb-0">Evaluación del desempeño, cumplimiento de metas y registros valorativos.</p>
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

<!-- Tarjetas de Balance de Méritos -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="fs-xs text-muted text-uppercase fw-semibold">Total de Registros</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= $totalConteo ?></h3>
                    <div class="fs-xs text-muted mt-1">Incidentes analizados</div>
                </div>
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                    <i class="bi bi-award fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm border-start border-4 border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="fs-xs text-muted text-uppercase fw-semibold">Logros Positivos</span>
                    <h3 class="fw-bold mb-0 text-success"><?= $conteoPositivos ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <?= $totalConteo > 0 ? number_format(($conteoPositivos / $totalConteo) * 100, 1) : 0 ?>% del total evaluado
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-star-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm border-start border-4 border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="fs-xs text-muted text-uppercase fw-semibold">Logros Negativos</span>
                    <h3 class="fw-bold mb-0 text-danger"><?= $conteoNegativos ?></h3>
                    <div class="fs-xs text-muted mt-1">
                        <?= $totalConteo > 0 ? number_format(($conteoNegativos / $totalConteo) * 100, 1) : 0 ?>% incidencias operativas
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros de Consulta: Empleado, Rango de Fechas y Tipo -->
<div class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body p-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-2 align-items-center']) ?>
            <div class="col-12 col-md-3">
                <label class="form-label fs-xs mb-1">Empleado:</label>
                <select name="empleado_id" class="form-select form-select-sm">
                    <option value="">Todos los Empleados</option>
                    <?php foreach ($empleados as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($empleadoId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label fs-xs mb-1">Tipo:</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="positivo" <?= ($tipo === 'positivo') ? 'selected' : '' ?>>Positivo</option>
                    <option value="negativo" <?= ($tipo === 'negativo') ? 'selected' : '' ?>>Negativo</option>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label fs-xs mb-1">Fecha Desde:</label>
                <input type="date" name="fecha_desde" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>" value="<?= h($fechaDesde) ?>">
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label fs-xs mb-1">Fecha Hasta:</label>
                <input type="date" name="fecha_hasta" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>" value="<?= h($fechaHasta) ?>">
            </div>

            <div class="col-6 col-md-3 d-flex align-items-end gap-1 pt-3">
                <button type="submit" class="btn btn-info btn-sm rounded-pill text-white w-100">
                    <i class="bi bi-filter me-1"></i>Aplicar Filtros
                </button>
                <?php if (!empty($empleadoId) || !empty($tipo) || !empty($fechaDesde) || !empty($fechaHasta)): ?>
                    <a href="<?= $this->Url->build(['action' => 'logros']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Tabla Detallada con Insignias de Color -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 120px;">Fecha</th>
                        <th>Empleado</th>
                        <th style="width: 130px;">Clasificación</th>
                        <th>Descripción del Mérito / Incidencia</th>
                        <th>Sucursal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logros)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No se encontraron logros registrados para los parámetros indicados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logros as $logro): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    <?= $logro->fecha_ocurrencia ? $logro->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : 'N/A' ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= h($logro->empleado->fotografia_url ?? '/img/empleados/avatar-default.svg') ?>" class="avatar-thumbnail me-2" alt="">
                                        <div>
                                            <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $logro->empleado_id]) ?>" class="fw-bold text-dark text-decoration-none">
                                                <?= h($logro->empleado->nombre_completo ?? 'Empleado') ?>
                                            </a>
                                            <div class="fs-xs text-muted"><?= h($logro->empleado->puesto->nombre ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($logro->isPositivo()): ?>
                                        <span class="badge badge-soft-success px-2 py-1 fs-xs">
                                            <i class="bi bi-star-fill me-1"></i>Positivo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-danger px-2 py-1 fs-xs">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Negativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-dark fs-sm"><?= h($logro->descripcion) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= h($logro->empleado->tienda->nombre ?? 'N/A') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
