<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Puesto $puesto
 */
$this->assign('title', 'Editar Puesto');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Modificar Puesto #<?= $puesto->id ?>
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
                            'required' => true,
                            'maxlength' => 100,
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <?= $this->Form->control('descripcion', [
                            'type' => 'textarea',
                            'label' => false,
                            'class' => 'form-control',
                            'rows' => 3,
                            'maxlength' => 255,
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
