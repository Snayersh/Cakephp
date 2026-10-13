<?php
/**
 * Perfil 360° / Kárdex del Empleado
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Empleado $empleado
 * @var int $logrosPositivos
 * @var int $logrosNegativos
 * @var int $totalLlamadas
 */
$this->assign('title', 'Perfil 360°: ' . $empleado->nombre_completo);
?>

<!-- Encabezado con Botones de Acción -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">
            <i class="bi bi-person-badge text-primary me-2"></i>Kárdex del Empleado (Perfil 360°)
        </h2>
        <p class="text-muted fs-sm mb-0">Expediente laboral integral, historial de desempeño y disciplina.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm no-print">
            <i class="bi bi-printer me-1"></i> Imprimir Kárdex
        </button>
        <a href="<?= $this->Url->build(['action' => 'edit', $empleado->id]) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm no-print">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light btn-sm rounded-pill px-3 no-print">
            <i class="bi bi-arrow-left me-1"></i> Regresar
        </a>
    </div>
</div>

<!-- Tarjeta Principal: Cabecera del Perfil con Fotografía en Alta Resolución -->
<div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="bg-primary bg-gradient p-4 text-white position-relative" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
        <div class="row align-items-center">
            <div class="col-12 col-md-auto text-center text-md-start mb-3 mb-md-0">
                <img src="<?= h($empleado->fotografia_url) ?>" 
                     alt="<?= h($empleado->nombre_completo) ?>" 
                     class="avatar-lg shadow-lg preview-photo-trigger cursor-pointer" 
                     data-full-src="<?= h($empleado->fotografia_url) ?>" 
                     data-caption="<?= h($empleado->nombre_completo) ?> &bull; <?= h($empleado->puesto->nombre ?? '') ?>"
                     title="Clic para ampliar imagen">
            </div>
            <div class="col-12 col-md text-center text-md-start">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                    <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 px-3 py-1 fs-xs">
                        <i class="bi bi-hash me-1"></i>ID Empleado: <?= $empleado->id ?>
                    </span>
                    <span class="badge bg-success text-white px-3 py-1 fs-xs">
                        <i class="bi bi-check-circle-fill me-1"></i>Personal Activo
                    </span>
                </div>
                <h2 class="fw-bold mb-1 text-white"><?= h($empleado->nombre_completo) ?></h2>
                <div class="fs-5 opacity-90 mb-2">
                    <i class="bi bi-briefcase me-1"></i><?= h($empleado->puesto->nombre ?? 'Puesto no asignado') ?>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 fs-sm opacity-75">
                    <span><i class="bi bi-shop me-1"></i><?= h($empleado->tienda->nombre ?? 'Sucursal no asignada') ?></span>
                    <span>&bull;</span>
                    <span><i class="bi bi-cash-stack me-1"></i><?= $empleado->salario_formateado ?> / mes</span>
                    <span>&bull;</span>
                    <span><i class="bi bi-cake2 me-1"></i><?= $empleado->edad ?> años de edad</span>
                </div>
            </div>
            <div class="col-12 col-md-auto text-center text-md-end mt-3 mt-md-0 no-print">
                <div class="d-flex flex-md-column gap-2 justify-content-center">
                    <a href="<?= $this->Url->build(['controller' => 'Logros', 'action' => 'add', '?' => ['empleado_id' => $empleado->id]]) ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i>Nuevo Logro
                    </a>
                    <a href="<?= $this->Url->build(['controller' => 'LlamadasAtencion', 'action' => 'add', '?' => ['empleado_id' => $empleado->id]]) ?>" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm text-dark">
                        <i class="bi bi-exclamation-triangle me-1"></i>Llamada Atención
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra Rápida de Indicadores Disciplinarios y Méritos -->
    <div class="bg-white p-3 border-top d-flex flex-wrap justify-content-around text-center">
        <div class="px-3 py-1">
            <span class="fs-xs text-muted d-block text-uppercase fw-semibold">Logros Positivos</span>
            <span class="fs-5 fw-bold text-success"><i class="bi bi-star-fill me-1"></i><?= $logrosPositivos ?></span>
        </div>
        <div class="vr opacity-25"></div>
        <div class="px-3 py-1">
            <span class="fs-xs text-muted d-block text-uppercase fw-semibold">Logros Negativos</span>
            <span class="fs-5 fw-bold text-danger"><i class="bi bi-hand-thumbs-down-fill me-1"></i><?= $logrosNegativos ?></span>
        </div>
        <div class="vr opacity-25"></div>
        <div class="px-3 py-1">
            <span class="fs-xs text-muted d-block text-uppercase fw-semibold">Llamadas de Atención</span>
            <span class="fs-5 fw-bold text-warning"><i class="bi bi-shield-exclamation me-1"></i><?= $totalLlamadas ?></span>
        </div>
        <div class="vr opacity-25"></div>
        <div class="px-3 py-1">
            <span class="fs-xs text-muted d-block text-uppercase fw-semibold">Salario Anual Proyectado</span>
            <span class="fs-5 fw-bold text-dark">Q <?= number_format((float)$empleado->salario * 12, 2) ?></span>
        </div>
    </div>
</div>

<!-- Secciones / Pestañas Detalladas del Kárdex 360° -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom px-4 pt-3 pb-0">
        <ul class="nav nav-tabs nav-tabs-kardex border-0" id="kardexTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab">
                    <i class="bi bi-person-lines-fill me-2"></i>1. Datos Generales y Laborales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logros-tab" data-bs-toggle="tab" data-bs-target="#logros-tab-pane" type="button" role="tab">
                    <i class="bi bi-award-fill me-2 text-success"></i>2. Historial de Logros (<?= count($empleado->logros ?? []) ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="llamadas-tab" data-bs-toggle="tab" data-bs-target="#llamadas-tab-pane" type="button" role="tab">
                    <i class="bi bi-shield-exclamation me-2 text-warning"></i>3. Llamadas de Atención (<?= count($empleado->llamadas_atencion ?? []) ?>)
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="kardexTabContent">
            <!-- Pestaña 1: Datos Generales y Edad Calculada -->
            <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-person text-primary me-2"></i>Información Personal
                            </h6>
                            <dl class="row mb-0 fs-sm">
                                <dt class="col-sm-5 text-muted">Nombres:</dt>
                                <dd class="col-sm-7 fw-semibold text-dark"><?= h($empleado->nombres) ?></dd>

                                <dt class="col-sm-5 text-muted">Apellidos:</dt>
                                <dd class="col-sm-7 fw-semibold text-dark"><?= h($empleado->apellidos) ?></dd>

                                <dt class="col-sm-5 text-muted">Fecha Nacimiento:</dt>
                                <dd class="col-sm-7 text-dark">
                                    <?= $empleado->fecha_nacimiento ? $empleado->fecha_nacimiento->i18nFormat('dd/MM/yyyy') : 'N/A' ?>
                                </dd>

                                <dt class="col-sm-5 text-muted">Edad Calculada:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge bg-primary px-3 py-1"><?= $empleado->edad ?> años cumplidos</span>
                                </dd>

                                <dt class="col-sm-5 text-muted">Fecha de Registro:</dt>
                                <dd class="col-sm-7 text-muted">
                                    <?= $empleado->creado_en ? $empleado->creado_en->i18nFormat('yyyy-MM-dd HH:mm:ss') : 'N/A' ?>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-building text-primary me-2"></i>Adscripción y Salario
                            </h6>
                            <dl class="row mb-0 fs-sm">
                                <dt class="col-sm-5 text-muted">Puesto Actual:</dt>
                                <dd class="col-sm-7 fw-bold text-primary"><?= h($empleado->puesto->nombre ?? 'N/A') ?></dd>

                                <dt class="col-sm-5 text-muted">Descripción Puesto:</dt>
                                <dd class="col-sm-7 text-secondary"><?= h($empleado->puesto->descripcion ?: 'Sin descripción') ?></dd>

                                <dt class="col-sm-5 text-muted">Tienda Asignada:</dt>
                                <dd class="col-sm-7 fw-semibold text-dark"><?= h($empleado->tienda->nombre ?? 'N/A') ?></dd>

                                <dt class="col-sm-5 text-muted">Dirección Tienda:</dt>
                                <dd class="col-sm-7 text-secondary"><?= h($empleado->tienda->direccion ?: 'N/A') ?></dd>

                                <dt class="col-sm-5 text-muted">Salario Mensual:</dt>
                                <dd class="col-sm-7 fw-bold text-success fs-6"><?= $empleado->salario_formateado ?> GTQ</dd>

                                <dt class="col-sm-5 text-muted">Salario Quincenal:</dt>
                                <dd class="col-sm-7 text-dark">Q <?= number_format((float)$empleado->salario / 2, 2) ?> GTQ</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña 2: Historial Cronológico de Logros -->
            <div class="tab-pane fade" id="logros-tab-pane" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-award text-success me-2"></i>Historial de Reconocimientos y Metas
                    </h6>
                    <a href="<?= $this->Url->build(['controller' => 'Logros', 'action' => 'add', '?' => ['empleado_id' => $empleado->id]]) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 no-print">
                        <i class="bi bi-plus-circle me-1"></i>Nuevo Logro
                    </a>
                </div>

                <?php if (empty($empleado->logros)): ?>
                    <div class="alert alert-light border text-center py-4 text-muted">
                        <i class="bi bi-award fs-3 d-block mb-2 text-secondary"></i>
                        No se registran reconocimientos o logros en el expediente de este empleado.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Fecha</th>
                                    <th style="width: 130px;">Tipo</th>
                                    <th>Descripción del Logro / Reconocimiento</th>
                                    <th class="text-end no-print" style="width: 100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empleado->logros as $logro): ?>
                                    <tr>
                                        <td class="fw-medium text-dark">
                                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                                            <?= $logro->fecha_ocurrencia ? $logro->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : '' ?>
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
                                        <td class="text-dark"><?= h($logro->descripcion) ?></td>
                                        <td class="text-end no-print">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= $this->Url->build(['controller' => 'Logros', 'action' => 'edit', $logro->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?= $this->Form->postLink(
                                                    '<i class="bi bi-trash"></i>',
                                                    ['controller' => 'Logros', 'action' => 'delete', $logro->id, '?' => ['from_profile' => 1]],
                                                    [
                                                        'escape' => false,
                                                        'class' => 'btn btn-outline-danger',
                                                        'data-confirm' => '¿Desea eliminar este registro de logro?',
                                                        'title' => 'Eliminar',
                                                    ]
                                                ) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pestaña 3: Historial de Llamadas de Atención -->
            <div class="tab-pane fade" id="llamadas-tab-pane" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-shield-exclamation text-warning me-2"></i>Historial Disciplinario e Incidencias
                    </h6>
                    <a href="<?= $this->Url->build(['controller' => 'LlamadasAtencion', 'action' => 'add', '?' => ['empleado_id' => $empleado->id]]) ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 text-dark no-print">
                        <i class="bi bi-plus-circle me-1"></i>Registrar Llamada de Atención
                    </a>
                </div>

                <?php if (empty($empleado->llamadas_atencion)): ?>
                    <div class="alert alert-light border text-center py-4 text-muted">
                        <i class="bi bi-shield-check fs-3 d-block mb-2 text-success"></i>
                        Expediente limpio: Este empleado no tiene ninguna llamada de atención registrada.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Fecha Incidencia</th>
                                    <th>Causa / Descripción de la Falta</th>
                                    <th class="text-end no-print" style="width: 100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empleado->llamadas_atencion as $llamada): ?>
                                    <tr>
                                        <td class="fw-medium text-dark">
                                            <i class="bi bi-calendar-x me-1 text-danger"></i>
                                            <?= $llamada->fecha_ocurrencia ? $llamada->fecha_ocurrencia->i18nFormat('yyyy-MM-dd') : '' ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <span class="badge badge-soft-warning me-2 mt-1">Llamada de Atención</span>
                                                <span class="text-dark"><?= h($llamada->descripcion) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end no-print">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= $this->Url->build(['controller' => 'LlamadasAtencion', 'action' => 'edit', $llamada->id]) ?>" class="btn btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?= $this->Form->postLink(
                                                    '<i class="bi bi-trash"></i>',
                                                    ['controller' => 'LlamadasAtencion', 'action' => 'delete', $llamada->id, '?' => ['from_profile' => 1]],
                                                    [
                                                        'escape' => false,
                                                        'class' => 'btn btn-outline-danger',
                                                        'data-confirm' => '¿Desea eliminar esta llamada de atención del expediente?',
                                                        'title' => 'Eliminar',
                                                    ]
                                                ) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
