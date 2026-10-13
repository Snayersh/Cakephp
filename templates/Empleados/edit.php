<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Empleado $empleado
 * @var iterable $puestos
 * @var iterable $tiendas
 */
$this->assign('title', 'Editar Empleado: ' . $empleado->nombre_completo);
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-6">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Editar Expediente de Empleado #<?= $empleado->id ?>
                    </h5>
                    <span class="fs-xs text-muted"><?= h($empleado->nombre_completo) ?></span>
                </div>
                <a href="<?= $this->Url->build(['action' => 'view', $empleado->id]) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar al Perfil
                </a>
            </div>

            <div class="card-body p-4">
                <?= $this->Form->create($empleado, ['type' => 'file', 'id' => 'formEmpleadoEdit']) ?>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombres">Nombres <span class="text-danger">*</span></label>
                            <?= $this->Form->control('nombres', [
                                'label' => false,
                                'class' => 'form-control',
                                'required' => true,
                                'maxlength' => 100,
                            ]) ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <?= $this->Form->control('apellidos', [
                                'label' => false,
                                'class' => 'form-control',
                                'required' => true,
                                'maxlength' => 100,
                            ]) ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fecha-nacimiento">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <?= $this->Form->control('fecha_nacimiento', [
                                'type' => 'date',
                                'label' => false,
                                'class' => 'form-control',
                                'required' => true,
                                'value' => $empleado->fecha_nacimiento ? $empleado->fecha_nacimiento->format('Y-m-d') : '',
                            ]) ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="salario">Salario Mensual Bruto (GTQ - Quetzales) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Q</span>
                                <?= $this->Form->control('salario', [
                                    'type' => 'number',
                                    'step' => '0.01',
                                    'min' => '0.01',
                                    'label' => false,
                                    'class' => 'form-control',
                                    'required' => true,
                                ]) ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="puesto-id">Puesto Laboral <span class="text-danger">*</span></label>
                            <?= $this->Form->control('puesto_id', [
                                'type' => 'select',
                                'options' => $puestos,
                                'label' => false,
                                'class' => 'form-select',
                                'required' => true,
                            ]) ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="tienda-id">Sucursal / Tienda <span class="text-danger">*</span></label>
                            <?= $this->Form->control('tienda_id', [
                                'type' => 'select',
                                'options' => $tiendas,
                                'label' => false,
                                'class' => 'form-select',
                                'required' => true,
                            ]) ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="fotografia-file-input">Actualizar Fotografía (Opcional)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <img id="fotografia-live-preview" 
                                         src="<?= h($empleado->fotografia_url) ?>" 
                                         alt="Fotografía Actual" 
                                         class="rounded-3 border shadow-sm avatar-lg" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <?= $this->Form->control('fotografia_file', [
                                        'type' => 'file',
                                        'id' => 'fotografia-file-input',
                                        'label' => false,
                                        'class' => 'form-control',
                                        'accept' => 'image/jpeg,image/png,image/webp',
                                    ]) ?>
                                    <div class="form-text">Si no desea cambiar la fotografía, deje este campo vacío.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="<?= $this->Url->build(['action' => 'view', $empleado->id]) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Actualizar Expediente
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
