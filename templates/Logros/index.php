<?php
/**
 * @var \Cake\View\View $this
 * @var iterable $logros
 * @var iterable $empleadosList
 * @var string|null $tipo
 * @var string|null $empleadoId
 */
$this->assign('title', 'Historial de Logros');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-award-fill text-success me-2"></i>Historial de Logros y Méritos
        </h2>
        <p class="text-muted fs-sm mb-0">Reconocimientos de desempeño e incidencias evaluativas del personal.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build(['controller' => 'Reportes', 'action' => 'logros']) ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-bar-chart-line me-1"></i> Reporte Estadístico
        </a>
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> Registrar Logro
        </a>
    </div>
</div>

<!-- Filtros Rápidos -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-2 align-items-center']) ?>
            <div class="col-12 col-md-5">
                <select name="empleado_id" class="form-select form-select-sm">
                    <option value="">Todos los Empleados</option>
                    <?php foreach ($empleadosList as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($empleadoId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-4">
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Cualquier Tipo (Positivo / Negativo)</option>
                    <option value="positivo" <?= ($tipo === 'positivo') ? 'selected' : '' ?>>Solo Positivos</option>
                    <option value="negativo" <?= ($tipo === 'negativo') ? 'selected' : '' ?>>Solo Negativos</option>
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-success btn-sm rounded-pill w-100">
                    <i class="bi bi-filter me-1"></i>Filtrar
                </button>
                <?php if (!empty($empleadoId) || !empty($tipo)): ?>
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Empleado</th>
                        <th>Tipo</th>
                        <th>Descripción del Logro</th>
                        <th>Tienda</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logros)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-award display-6 d-block mb-2 text-muted"></i>
                                No se encontraron registros de logros.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logros as $logro): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark" style="white-space: nowrap;">
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
                                        <span class="badge badge-soft-success px-2 py-1">
                                            <i class="bi bi-star-fill me-1"></i>Positivo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-danger px-2 py-1">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Negativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="text-dark fs-sm"><?= h($logro->descripcion) ?></span></td>
                                <td><span class="badge bg-light text-dark border"><?= h($logro->empleado->tienda->nombre ?? 'N/A') ?></span></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $this->Url->build(['action' => 'edit', $logro->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $logro->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'data-confirm' => '¿Está seguro de eliminar este registro de logro?',
                                                'title' => 'Eliminar',
                                            ]
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
