<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\LlamadaAtencion $llamada
 * @var iterable $empleados
 * @var string|null $targetEmpleadoId
 */
$this->assign('title', 'Registrar Llamada de Atención');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-shield-exclamation text-warning me-2"></i>Registrar Llamada de Atención
                </h5>
                <a href="<?= !empty($targetEmpleadoId) ? $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $targetEmpleadoId]) : $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($llamada) ?>
                    <?php if (!empty($targetEmpleadoId)): ?>
                        <input type="hidden" name="redirect_empleado" value="<?= h($targetEmpleadoId) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="empleado-id">Empleado Sancionado <span class="text-danger">*</span></label>
                        <?= $this->Form->control('empleado_id', [
                            'type' => 'select',
                            'options' => $empleados,
                            'empty' => '-- Seleccione un Empleado --',
                            'label' => false,
                            'class' => 'form-select',
                            'required' => true,
                            'value' => $llamada->empleado_id,
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fecha-ocurrencia">Fecha de la Incidencia <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="descripcion">Motivo / Descripción de la Falta <span class="text-danger">*</span></label>
                        <?= $this->Form->control('descripcion', [
                            'type' => 'textarea',
                            'rows' => 4,
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Describa el hecho, falta al reglamento interno o incumplimiento en el servicio...',
                            'required' => true,
                            'maxlength' => 500,
                        ]) ?>
                        <div class="form-text">Máximo 500 caracteres.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-medium">
                            <i class="bi bi-check-circle me-1"></i>Registrar Llamada
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
