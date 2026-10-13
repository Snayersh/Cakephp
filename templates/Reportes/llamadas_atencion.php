<?php
/**
 * Reporte Cronológico de Llamadas de Atención
 * @var \Cake\View\View $this
 * @var iterable $llamadas
 * @var iterable $empleados
 * @var iterable $tiendas
 * @var string|null $empleadoId
 * @var string|null $tiendaId
 * @var string|null $fechaDesde
 * @var string|null $fechaHasta
 */
$this->assign('title', 'Reporte de Llamadas de Atención');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-shield-exclamation text-warning me-2"></i>Reporte de Llamadas de Atención
        </h2>
        <p class="text-muted fs-sm mb-0">Listado cronológico de incidencias, amonestaciones y faltas al reglamento.</p>
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

<!-- Filtros de Consulta: Empleado, Sucursal y Rango de Fechas -->
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

            <div class="col-6 col-md-3">
                <label class="form-label fs-xs mb-1">Sucursal / Tienda:</label>
                <select name="tienda_id" class="form-select form-select-sm">
                    <option value="">Todas las Sucursales</option>
                    <?php foreach ($tiendas as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($tiendaId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label fs-xs mb-1">Fecha Desde:</label>
                <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= h($fechaDesde) ?>">
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label fs-xs mb-1">Fecha Hasta:</label>
                <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= h($fechaHasta) ?>">
            </div>

            <div class="col-6 col-md-2 d-flex align-items-end gap-1 pt-3">
                <button type="submit" class="btn btn-warning btn-sm rounded-pill w-100 text-dark fw-medium">
                    <i class="bi bi-filter me-1"></i>Filtrar
                </button>
                <?php if (!empty($empleadoId) || !empty($tiendaId) || !empty($fechaDesde) || !empty($fechaHasta)): ?>
                    <a href="<?= $this->Url->build(['action' => 'llamadasAtencion']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Listado Cronológico de Incidencias -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 fs-6">
            <i class="bi bi-list-check text-warning me-2"></i>Incidencias Registradas (<?= count($llamadas) ?>)
        </h5>
        <span class="fs-xs text-muted">Ordenadas cronológicamente de la más reciente a la más antigua</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 130px;">Fecha Incidencia</th>
                        <th>Empleado</th>
                        <th>Puesto</th>
                        <th>Sucursal / Tienda</th>
                        <th>Causa / Descripción de la Falta</th>
                        <th class="text-center" style="width: 140px;">Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($llamadas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check display-6 d-block mb-2 text-success"></i>
                                No se registran incidencias para los criterios seleccionados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($llamadas as $llamada): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <i class="bi bi-calendar-x me-1 text-danger"></i>
                                    <?= $llamada->fecha_ocurrencia ? $llamada->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : 'N/A' ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= h($llamada->empleado->fotografia_url ?? '/img/empleados/avatar-default.svg') ?>" class="avatar-thumbnail me-2" alt="">
                                        <div>
                                            <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]) ?>" class="fw-bold text-dark text-decoration-none">
                                                <?= h($llamada->empleado->nombre_completo ?? 'Empleado') ?>
                                            </a>
                                            <div class="fs-xs text-muted">ID: #<?= $llamada->empleado_id ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= h($llamada->empleado->puesto->nombre ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-shop me-1"></i><?= h($llamada->empleado->tienda->nombre ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="text-dark fs-sm"><?= h($llamada->descripcion) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-soft-warning px-2 py-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Amonestación
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
