<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Tienda $tienda
 */
$this->assign('title', 'Registrar Tienda');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0 fs-6">
                    <i class="bi bi-shop text-primary me-2"></i>Registrar Nueva Tienda
                </h5>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create($tienda, ['novalidate' => false]) ?>
                    <div class="mb-3">
                        <label class="form-label" for="nombre">Nombre de la Tienda <span class="text-danger">*</span></label>
                        <?= $this->Form->control('nombre', [
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Ej. Sterenfix Matriz Centro',
                            'required' => true,
                            'maxlength' => 100,
                        ]) ?>
                        <div class="form-text">Debe ser un nombre único para identificar la sucursal.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="direccion">Dirección Física</label>
                        <?= $this->Form->control('direccion', [
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Calle, Número, Colonia, Ciudad',
                            'maxlength' => 255,
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="telefono">Teléfono de Contacto</label>
                        <?= $this->Form->control('telefono', [
                            'label' => false,
                            'class' => 'form-control',
                            'placeholder' => '+52 55 1234 5678',
                            'maxlength' => 20,
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Guardar Tienda
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
