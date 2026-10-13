<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\Empleado $empleado
 * @var iterable $puestos
 * @var iterable $tiendas
 */
$this->assign('title', 'Registrar Empleado');
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-6">
                        <i class="bi bi-person-plus-fill text-primary me-2"></i>Alta de Nuevo Empleado Sterenfix
                    </h5>
                    <span class="fs-xs text-muted">Complete los datos generales, puesto asignado, salario y fotografía.</span>
                </div>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Regresar
                </a>
            </div>

            <div class="card-body p-4">
                <?= $this->Form->create($empleado, ['type' => 'file', 'id' => 'formEmpleado']) ?>
                    <div class="row g-3">
                        <!-- Nombres y Apellidos Separados -->
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombres">Nombres <span class="text-danger">*</span></label>
                            <?= $this->Form->control('nombres', [
                                'label' => false,
                                'class' => 'form-control',
                                'placeholder' => 'Ej. Juan Carlos',
                                'required' => true,
                                'maxlength' => 100,
                            ]) ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <?= $this->Form->control('apellidos', [
                                'label' => false,
                                'class' => 'form-control',
                                'placeholder' => 'Ej. Pérez López',
                                'required' => true,
                                'maxlength' => 100,
                            ]) ?>
                        </div>

                        <!-- Fecha de Nacimiento con selector -->
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fecha-nacimiento">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <?= $this->Form->control('fecha_nacimiento', [
                                'type' => 'date',
                                'label' => false,
                                'class' => 'form-control',
                                'required' => true,
                                'max' => date('Y-m-d', strtotime('-18 years')),
                            ]) ?>
                            <div class="form-text">El empleado debe ser mayor de edad (&gt;= 18 años).</div>
                        </div>

                        <!-- Salario Numérico con Moneda -->
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="salario">Salario Mensual Bruto (MXN) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">$</span>
                                <?= $this->Form->control('salario', [
                                    'type' => 'number',
                                    'step' => '0.01',
                                    'min' => '0.01',
                                    'label' => false,
                                    'class' => 'form-control',
                                    'placeholder' => '0.00',
                                    'required' => true,
                                ]) ?>
                            </div>
                            <div class="form-text">El salario debe ser un valor positivo mayor a cero.</div>
                        </div>

                        <!-- Selectores Desplegables: Puesto y Tienda -->
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="puesto-id">Puesto Laboral <span class="text-danger">*</span></label>
                            <?= $this->Form->control('puesto_id', [
                                'type' => 'select',
                                'options' => $puestos,
                                'empty' => '-- Seleccione un Puesto --',
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
                                'empty' => '-- Seleccione una Sucursal --',
                                'label' => false,
                                'class' => 'form-select',
                                'required' => true,
                            ]) ?>
                        </div>

                        <!-- Carga de Archivo Fotográfico con Previsualización -->
                        <div class="col-12">
                            <label class="form-label" for="fotografia-file-input">Fotografía del Empleado (Opcional)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <img id="fotografia-live-preview" 
                                         src="/img/empleados/avatar-default.svg" 
                                         alt="Previsualización" 
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
                                    <div class="form-text">Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 5 MB. Se guardará en <code>/webroot/img/empleados/</code>.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i>Registrar Empleado
                        </button>
                    </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
