<?php
/**
 * Reporte General de Empleados con Totalización de Salarios
 * @var \Cake\View\View $this
 * @var iterable $empleados
 * @var float $totalSalarios
 * @var float $promedioSalario
 * @var iterable $tiendas
 * @var iterable $puestos
 * @var string|null $tiendaId
 * @var string|null $puestoId
 * @var string|null $search
 */
$this->assign('title', 'Reporte General de Empleados y Nómina');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-file-earmark-person-fill text-primary me-2"></i>Reporte General de Empleados
        </h2>
        <p class="text-muted fs-sm mb-0">Consolidado general de personal con totalización salarial de la empresa.</p>
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

<!-- Tarjetas Destacadas de Totalización General de Salarios -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card p-3 border-0 shadow-sm bg-primary bg-gradient text-white" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
            <span class="fs-xs text-uppercase opacity-75 fw-semibold">Totalización General de Salarios</span>
            <h2 class="fw-bold mb-0 text-white">$<?= number_format($totalSalarios, 2) ?> <span class="fs-6 fw-normal">MXN</span></h2>
            <div class="fs-xs opacity-75 mt-1">Presupuesto mensual consolidado</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card p-3 border-0 shadow-sm">
            <span class="fs-xs text-muted text-uppercase fw-semibold">Personal Contabilizado</span>
            <h2 class="fw-bold mb-0 text-dark"><?= count($empleados) ?> <span class="fs-6 fw-normal text-muted">colaboradores</span></h2>
            <div class="fs-xs text-muted mt-1">Registrados en la selección actual</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card p-3 border-0 shadow-sm">
            <span class="fs-xs text-muted text-uppercase fw-semibold">Salario Promedio Individual</span>
            <h2 class="fw-bold mb-0 text-success">$<?= number_format($promedioSalario, 2) ?> <span class="fs-6 fw-normal text-muted">MXN</span></h2>
            <div class="fs-xs text-muted mt-1">Media salarial por empleado</div>
        </div>
    </div>
</div>

<!-- Filtros de Búsqueda -->
<div class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body p-3">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-2 align-items-center']) ?>
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar por nombres o apellidos..." value="<?= h($search) ?>">
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
                    <a href="<?= $this->Url->build(['action' => 'empleados']) ?>" class="btn btn-light btn-sm rounded-pill" title="Limpiar Filtros">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Tabla Interactiva del Reporte -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Fotografía</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Fecha Nacimiento</th>
                        <th>Edad</th>
                        <th>Puesto</th>
                        <th>Tienda / Sucursal</th>
                        <th class="text-end pe-4">Salario (MXN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($empleados)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                No se encontraron registros de empleados con los filtros aplicados.
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
                                <td class="fw-bold text-dark">
                                    <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $emp->id]) ?>" class="text-decoration-none text-dark">
                                        <?= h($emp->nombres) ?>
                                    </a>
                                </td>
                                <td class="fw-semibold text-secondary"><?= h($emp->apellidos) ?></td>
                                <td class="text-muted fs-sm">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= $emp->fecha_nacimiento ? $emp->fecha_nacimiento->i18nFormat('yyyy-MM-dd') : 'N/A' ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= $emp->edad ?> años</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary"><?= h($emp->puesto->nombre ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-shop me-1"></i><?= h($emp->tienda->nombre ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-bold text-success fs-6">
                                    <?= $emp->salario_formateado ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <!-- Pie de Tabla con la Totalización General Destacada -->
                <tfoot class="table-primary border-top border-2 border-primary">
                    <tr class="fw-bold">
                        <td colspan="7" class="ps-4 text-uppercase fs-sm text-primary py-3">
                            <i class="bi bi-calculator me-1"></i> Totalización General de Salarios (<?= count($empleados) ?> Empleados):
                        </td>
                        <td class="text-end pe-4 text-success fs-5 py-3">
                            $<?= number_format($totalSalarios, 2) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
