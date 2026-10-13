<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Tienda $tienda
 * @var float $subtotalNomina
 */
$this->assign('title', 'Detalle de Tienda: ' . $tienda->nombre);
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-shop text-primary me-2"></i><?= h($tienda->nombre) ?>
        </h2>
        <p class="text-muted fs-sm mb-0">
            <i class="bi bi-geo-alt me-1"></i><?= h($tienda->direccion ?: 'Sin dirección registrada') ?> | 
            <i class="bi bi-telephone me-1"></i><?= h($tienda->telefono ?: 'Sin teléfono') ?>
        </p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $tienda->id]) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Regresar
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm">
            <div class="fs-xs text-muted text-uppercase fw-semibold">Personal en Tienda</div>
            <h3 class="fw-bold mb-0 text-dark"><?= count($tienda->empleados ?? []) ?> empleados</h3>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm">
            <div class="fs-xs text-muted text-uppercase fw-semibold">Subtotal Nómina Mensual</div>
            <h3 class="fw-bold mb-0 text-success">$<?= number_format($subtotalNomina, 2) ?></h3>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 border-0 shadow-sm">
            <div class="fs-xs text-muted text-uppercase fw-semibold">Salario Promedio</div>
            <h3 class="fw-bold mb-0 text-info">
                $<?= number_format(count($tienda->empleados ?? []) > 0 ? ($subtotalNomina / count($tienda->empleados)) : 0, 2) ?>
            </h3>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 fs-6">
            <i class="bi bi-people text-primary me-2"></i>Plantilla de Empleados Asignados
        </h5>
        <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'add']) ?>" class="btn btn-sm btn-primary rounded-pill px-3">
            <i class="bi bi-person-plus me-1"></i> Asignar Empleado
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Empleado</th>
                    <th>Puesto</th>
                    <th>Fecha Nacimiento</th>
                    <th>Edad</th>
                    <th class="text-end pe-4">Salario</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tienda->empleados)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No hay empleados asignados a esta tienda actualmente.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tienda->empleados as $emp): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= h($emp->fotografia_url) ?>" alt="<?= h($emp->nombre_completo) ?>" class="avatar-thumbnail me-2">
                                    <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $emp->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                        <?= h($emp->nombre_completo) ?>
                                    </a>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-secondary border"><?= h($emp->puesto->nombre ?? 'N/A') ?></span></td>
                            <td><?= $emp->fecha_nacimiento ? $emp->fecha_nacimiento->i18nFormat('yyyy-MM-dd') : 'N/A' ?></td>
                            <td><?= $emp->edad ?> años</td>
                            <td class="text-end pe-4 fw-bold text-success"><?= $emp->salario_formateado ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
