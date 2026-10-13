<?php
/**
 * @var \Cake\View\View $this
 * @var iterable $empleados
 * @var iterable $tiendas
 * @var iterable $puestos
 * @var string|null $search
 * @var string|null $tiendaId
 * @var string|null $puestoId
 */
$this->assign('title', 'Padrón de Empleados');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-people-fill text-primary me-2"></i>Padrón de Empleados Sterenfix
        </h2>
        <p class="text-muted fs-sm mb-0">Gestión de personal técnico, administrativo y comercial.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build(['controller' => 'Reportes', 'action' => 'empleados']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Reporte Salarial
        </a>
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Registrar Empleado
        </a>
    </div>
</div>

<!-- Filtros de Búsqueda Dinámica -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-2 align-items-center']) ?>
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombres o apellidos..." value="<?= h($search) ?>">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="tienda_id" class="form-select form-select-sm">
                    <option value="">Todas las Tiendas</option>
                    <?php foreach ($tiendas as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($tiendaId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="puesto_id" class="form-select form-select-sm">
                    <option value="">Todos los Puestos</option>
                    <?php foreach ($puestos as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($puestoId == $id) ? 'selected' : '' ?>><?= h($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">
                    <i class="bi bi-filter me-1"></i>Filtrar
                </button>
                <?php if (!empty($search) || !empty($tiendaId) || !empty($puestoId)): ?>
                    <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Tabla de Empleados con Miniaturas y Acciones -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Foto</th>
                        <th>Nombres y Apellidos</th>
                        <th>Puesto</th>
                        <th>Sucursal / Tienda</th>
                        <th>Edad</th>
                        <th class="text-end">Salario</th>
                        <th class="text-center">Historial</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($empleados)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-6 d-block mb-2 text-muted"></i>
                                No se encontraron empleados con los criterios de búsqueda seleccionados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($empleados as $emp): ?>
                            <tr>
                                <td class="ps-4" style="width: 60px;">
                                    <img src="<?= h($emp->fotografia_url) ?>" 
                                         alt="<?= h($emp->nombre_completo) ?>" 
                                         class="avatar-thumbnail" 
                                         data-full-src="<?= h($emp->fotografia_url) ?>" 
                                         data-caption="<?= h($emp->nombre_completo) ?> &bull; <?= h($emp->puesto->nombre ?? '') ?>" 
                                         title="Clic para ampliar fotografía">
                                </td>
                                <td>
                                    <a href="<?= $this->Url->build(['action' => 'view', $emp->id]) ?>" class="fw-bold text-dark text-decoration-none">
                                        <?= h($emp->nombres) ?> <span class="fw-normal"><?= h($emp->apellidos) ?></span>
                                    </a>
                                    <div class="fs-xs text-muted">
                                        F. Nacimiento: <?= $emp->fecha_nacimiento ? $emp->fecha_nacimiento->i18nFormat('yyyy-MM-dd') : 'N/A' ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= h($emp->puesto->nombre ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="fs-xs text-dark fw-medium"><i class="bi bi-shop me-1 text-primary"></i><?= h($emp->tienda->nombre ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary"><?= $emp->edad ?> años</span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    <?= $emp->salario_formateado ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success me-1" title="Logros registrados">
                                        <i class="bi bi-award-fill me-1"></i><?= count($emp->logros ?? []) ?>
                                    </span>
                                    <span class="badge badge-soft-warning" title="Llamadas de atención">
                                        <i class="bi bi-shield-exclamation me-1"></i><?= count($emp->llamadas_atencion ?? []) ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $this->Url->build(['action' => 'view', $emp->id]) ?>" class="btn btn-outline-info" title="Ver Perfil 360° / Kárdex">
                                            <i class="bi bi-person-badge"></i>
                                        </a>
                                        <a href="<?= $this->Url->build(['action' => 'edit', $emp->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $emp->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'data-confirm' => '¿Está seguro de dar de baja al empleado "' . h($emp->nombre_completo) . '"?',
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
