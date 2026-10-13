<?php
/**
 * @var \Cake\View\View $this
 * @var iterable $tiendas
 */
$this->assign('title', 'Catálogo de Tiendas');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-shop text-primary me-2"></i>Tiendas y Sucursales Sterenfix
        </h2>
        <p class="text-muted fs-sm mb-0">Gestión de puntos de venta y centros de servicio técnico.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> Nueva Tienda
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
                        <th>Nombre de la Sucursal</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th class="text-center">Plantilla Activa</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tiendas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No hay tiendas registradas en el catálogo.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tiendas as $tienda): ?>
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">#<?= $tienda->id ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= h($tienda->nombre) ?></div>
                                    <span class="fs-xs text-muted">Registrada: <?= $tienda->creado_en ? $tienda->creado_en->i18nFormat('yyyy-MM-dd') : 'N/A' ?></span>
                                </td>
                                <td><span class="fs-sm text-secondary"><?= h($tienda->direccion ?: 'Sin dirección') ?></span></td>
                                <td><span class="badge bg-light text-dark border"><i class="bi bi-telephone me-1"></i><?= h($tienda->telefono ?: 'N/A') ?></span></td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-person me-1"></i><?= count($tienda->empleados ?? []) ?> empleados
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= $this->Url->build(['action' => 'view', $tienda->id]) ?>" class="btn btn-outline-secondary" title="Ver Tienda">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= $this->Url->build(['action' => 'edit', $tienda->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $tienda->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-outline-danger',
                                                'data-confirm' => '¿Está seguro de eliminar la tienda "' . h($tienda->nombre) . '"?',
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
