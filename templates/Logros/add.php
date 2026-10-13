<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Logro $logro
 * @var iterable $empleados
 * @var string|null $targetEmpleadoId
 */
$this->assign('title', 'Registrar Logro');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-award text-success me-2"></i>Registrar Logro / Mérito
                </h5>
                <a href="<?= !empty($targetEmpleadoId) ? $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $targetEmpleadoId]) : $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($logro) ?>
                    <?php if (!empty($targetEmpleadoId)): ?>
                        <input type="hidden" name="redirect_empleado" value="<?= h($targetEmpleadoId) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="empleado-id">Empleado Asociado <span class="text-danger">*</span></label>
                        <?= $this->Form->control('empleado_id', [
                            'type' => 'select',
                            'options' => $empleados,
                            'empty' => '-- Seleccione un Empleado --',
                            'label' => false,
                            'class' => 'form-select',
                            'required' => true,
                            'value' => $logro->empleado_id,
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tipo">Tipo de Logro <span class="text-danger">*</span></label>
                        <?= $this->Form->control('tipo', [
                            'type' => 'select',
                            'options' => [
                                'positivo' => 'Positivo (Reconocimiento / Meta superada)',
                                'negativo' => 'Negativo (Incidencia negativa / Falla operativa)',
                            ],
                            'empty' => '-- Seleccione Tipo --',
                            'label' => false,
                            'class' => 'form-select',
                            'required' => true,
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fecha-ocurrencia">Fecha de Ocurrencia <span class="text-danger">*</span></label>
                        <?= $this->Form->control('fecha_ocurrencia', [
                            'type' => 'date',
                            'label' => false,
                            'class' => 'form-control',
                            'required' => true,
                            'max' => date('Y-m-d'),
                            'value' => date('Y-m-d'),
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="descripcion">Descripción Detallada <span class="text-danger">*</span></label>
                        <?= $this->Form->control('descripcion', [
                            'type' => 'textarea',
                            'rows' => 4,
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Detalle la meta alcanzada, reconocimiento obtenido o acontecimiento relevante...',
                            'required' => true,
                            'maxlength' => 500,
                        ]) ?>
                        <div class="form-text">Máximo 500 caracteres.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Guardar Registro
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
