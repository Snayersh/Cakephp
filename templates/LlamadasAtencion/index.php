<?php
/**
 * @var \Cake\View\View $this
 * @var iterable $llamadas
 * @var iterable $empleadosList
 * @var string|null $empleadoId
 */
$this->assign('title', 'Llamadas de Atención');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-shield-exclamation text-warning me-2"></i>Historial de Llamadas de Atención
        </h2>
        <p class="text-muted fs-sm mb-0">Seguimiento disciplinario e incidencias de cumplimiento en Sterenfix.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build(['controller' => 'Reportes', 'action' => 'llamadasAtencion']) ?>" class="btn btn-outline-warning btn-sm rounded-pill px-3 shadow-sm text-dark">
            <i class="bi bi-journal-text me-1"></i> Reporte Cronológico
        </a>
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm text-dark">
            <i class="bi bi-plus-circle-fill me-1"></i> Registrar Llamada
        </a>
    </div>
</div>

<!-- Filtros por Empleado -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-2 align-items-center']) ?>
            <div class="col-12 col-md-9">
                <select name="empleado_id" class="form-select form-select-sm">
                    <option value="">Todos los Empleados</option>
                    <?php foreach ($empleadosList as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($empleadoId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-warning btn-sm rounded-pill w-100 text-dark">
                    <i class="bi bi-filter me-1"></i>Filtrar
                </button>
                <?php if (!empty($empleadoId)): ?>
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar Filtro">
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
                        <th class="ps-4">Fecha Falta</th>
                        <th>Empleado Sancionado</th>
                        <th>Causa / Descripción de la Falta</th>
                        <th>Sucursal</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($llamadas)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check display-6 d-block mb-2 text-success"></i>
                                No se encontraron llamadas de atención registradas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($llamadas as $llamada): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark" style="white-space: nowrap;">
                                    <i class="bi bi-calendar3 me-1 text-danger"></i>
                                    <?= $llamada->fecha_ocurrencia ? $llamada->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : 'N/A' ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= h($llamada->empleado->fotografia_url ?? '/img/empleados/avatar-default.svg') ?>" class="avatar-thumbnail me-2" alt="">
                                        <div>
                                            <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]) ?>" class="fw-bold text-dark text-decoration-none">
                                                <?= h($llamada->empleado->nombre_completo ?? 'Empleado') ?>
                                            </a>
                                            <div class="fs-xs text-muted"><?= h($llamada->empleado->puesto->nombre ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-warning me-1">Amonestación</span>
                                    <span class="text-dark fs-sm"><?= h($llamada->descripcion) ?></span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= h($llamada->empleado->tienda->nombre ?? 'N/A') ?></span></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $this->Url->build(['action' => 'edit', $llamada->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $llamada->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'data-confirm' => '¿Está seguro de eliminar esta llamada de atención?',
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
