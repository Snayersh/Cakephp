<?php
/**
 * @var \Cake\View\View $this
 * @var iterable $puestos
 */
$this->assign('title', 'Catálogo de Puestos');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-briefcase-fill text-primary me-2"></i>Catálogo de Puestos Laborales
        </h2>
        <p class="text-muted fs-sm mb-0">Estructura organizacional y perfiles de puesto en Sterenfix.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Puesto
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre del Puesto</th>
                        <th>Descripción del Rol</th>
                        <th class="text-center">Personal Ocupante</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($puestos)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No se registran puestos de trabajo en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($puestos as $puesto): ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">#<?= $puesto->id ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= h($puesto->nombre) ?></div>
                                    <span class="fs-xs text-muted">Registrado: <?= $puesto->creado_en ? $puesto->creado_en->i18nFormat('yyyy-MM-dd') : 'N/A' ?></span>
                                </td>
                                <td>
                                    <span class="fs-sm text-secondary"><?= h($puesto->descripcion ?: 'Sin descripción detallada') ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                        <i class="bi bi-people me-1"></i><?= count($puesto->empleados ?? []) ?> colaboradores
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $this->Url->build(['action' => 'view', $puesto->id]) ?>" class="btn btn-outline-secondary" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= $this->Url->build(['action' => 'edit', $puesto->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $puesto->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'data-confirm' => '¿Está seguro de eliminar el puesto "' . h($puesto->nombre) . '"?',
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
