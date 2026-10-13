<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\LlamadaAtencion $llamada
 * @var iterable $empleados
 */
$this->assign('title', 'Editar Llamada de Atención');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Editar Llamada de Atención #<?= $llamada->id ?>
                </h5>
                <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($llamada) ?>
                    <div class="mb-3">
                        <label class="form-label" for="empleado-id">Empleado</label>
                        <?= $this->Form->control('empleado_id', [
                            'type' => 'select',
                            'options' => $empleados,
                            'label' => false,
                            'class' => 'form-select',
                            'required' => true,
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fecha-ocurrencia">Fecha de Incidencia</label>
                        <?= $this->Form->control('fecha_ocurrencia', [
                            'type' => 'date',
                            'label' => false,
                            'class' => 'form-control',
                            'required' => true,
                            'value' => $llamada->fecha_ocurrencia ? $llamada->fecha_ocurrencia->format('Y-m-d') : '',
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <?= $this->Form->control('descripcion', [
                            'type' => 'textarea',
                            'rows' => 4,
                            'label' => false,
                            'class' => 'form-control',
                            'required' => true,
                            'maxlength' => 500,
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['controller' => 'Empleados', 'action' => 'view', $llamada->empleado_id]) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-medium">
                            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
