<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Puesto $puesto
 */
$this->assign('title', 'Registrar Puesto');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-briefcase text-primary me-2"></i>Registrar Puesto Laboral
                </h5>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($puesto) ?>
                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre del Puesto <span class="text-danger">*</span></label>
                        <?= $this->Form->control('nombre', [
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Ej. Ingeniero de Soporte Técnico',
                            'required' => true,
                            'maxlength' => 100,
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="descripcion">Descripción de Responsabilidades</label>
                        <?= $this->Form->control('descripcion', [
                            'type' => 'textarea',
                            'label' => false,
                            'class' => 'form-control',
                            'rows' => 3,
                            'placeholder' => 'Detalle de las funciones principales y perfil requerido...',
                            'maxlength' => 255,
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Guardar Puesto
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
