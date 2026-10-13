<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top px-3 py-2">
    <div class="container-fluid px-0">
        <button class="btn btn-light d-lg-none me-2" id="sidebarToggleBtn" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list fs-5"></i>
        </button>

        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-sm">
                    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/') ?>" class="text-decoration-none text-muted">Sterenfix</a></li>
                    <li class="breadcrumb-item active text-primary fw-medium" aria-current="page"><?= h($currentController) ?></li>
                    <?php if (!empty($currentAction) && $currentAction !== 'index'): ?>
                        <li class="breadcrumb-item active text-muted" aria-current="page"><?= ucfirst(h($currentAction)) ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <!-- Botón de Acción Rápida -->
            <div class="dropdown">
                <button class="btn btn-sm btn-primary rounded-pill px-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-circle me-1"></i> Registrar
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item py-2" href="<?= $this->Url->build('/empleados/add') ?>"><i class="bi bi-person-plus text-primary me-2"></i>Nuevo Empleado</a></li>
                    <li><a class="dropdown-item py-2" href="<?= $this->Url->build('/logros/add') ?>"><i class="bi bi-award text-success me-2"></i>Nuevo Logro</a></li>
                    <li><a class="dropdown-item py-2" href="<?= $this->Url->build('/llamadas-atencion/add') ?>"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Nueva Llamada Atención</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2" href="<?= $this->Url->build('/tiendas/add') ?>"><i class="bi bi-shop text-info me-2"></i>Nueva Tienda</a></li>
                    <li><a class="dropdown-item py-2" href="<?= $this->Url->build('/puestos/add') ?>"><i class="bi bi-briefcase text-secondary me-2"></i>Nuevo Puesto</a></li>
                </ul>
            </div>

            <!-- Perfil de Usuario Administrador -->
            <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 user-avatar-small">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="d-none d-md-block text-start me-2">
                    <div class="fs-xs fw-semibold lh-1">Administrador</div>
                    <div class="fs-xxs text-muted lh-1">Sterenfix Corp</div>
                </div>
            </div>
        </div>
    </div>
</header>
