<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Puesto $puesto
 */
$this->assign('title', 'Detalle de Puesto: ' . $puesto->nombre);
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-briefcase text-primary me-2"></i><?= h($puesto->nombre) ?>
        </h2>
        <p class="text-muted fs-sm mb-0"><?= h($puesto->descripcion ?: 'Sin descripción detallada') ?></p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <a href="<?= $this->Url->build(['action' => 'edit', $puesto->id]) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Regresar
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <h5 class="fw-bold text-dark mb-0 fs-6">
            <i class="bi bi-people text-primary me-2"></i>Empleados en esta Posición (<?= count($puesto->empleados ?? []) ?>)
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Empleado</th>
                    <th>Tienda Asignada</th>
                    <th>Fecha Nacimiento</th>
                    <th class="text-end pe-4">Salario</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($puesto->empleados)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No hay empleados asignados a este puesto actualmente.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($puesto->empleados as $emp): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= h($emp->fotografia_url) ?>" alt="<?= h($emp->nombre_completo) ?>" class="avatar-thumbnail me-2">
                                    <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $emp->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                        <?= h($emp->nombre_completo) ?>
                                    </a>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= h($emp->tienda->nombre ?? 'N/A') ?></span></td>
                            <td><?= $emp->fecha_nacimiento ? $emp->fecha_nacimiento->i18nFormat('yyyy-MM-dd') : 'N/A' ?></td>
                            <td class="text-end pe-4 fw-bold text-success"><?= $emp->salario_formateado ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
