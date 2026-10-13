<aside class="sidebar bg-dark text-white shadow-sm" id="mainSidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
        <a href="<?= $this->Url->build('/') ?>" class="d-flex align-items-center text-white text-decoration-none">
            <div class="brand-icon me-2 rounded-3 d-flex align-items-center justify-content-center bg-primary text-white shadow-sm">
                <i class="bi bi-cpu-fill fs-5"></i>
            </div>
            <div>
                <span class="fs-5 fw-bold tracking-tight text-white">Steren<span class="text-primary-emphasis text-info">fix</span></span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-block fs-xs fw-normal mt-0">Personal v1.0</span>
            </div>
        </a>
        <button class="btn btn-sm btn-link text-white-50 d-lg-none" id="sidebarCloseBtn">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    <div class="sidebar-menu p-3">
        <div class="menu-section-label text-uppercase text-white-50 fs-xs fw-semibold px-2 mb-2">Panel Principal</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="<?= $this->Url->build('/') ?>" class="nav-link <?= ($currentController === 'Dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <div class="menu-section-label text-uppercase text-white-50 fs-xs fw-semibold px-2 mb-2">Catálogos Base</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="<?= $this->Url->build('/tiendas') ?>" class="nav-link <?= ($currentController === 'Tiendas') ? 'active' : '' ?>">
                    <i class="bi bi-shop me-2"></i>
                    <span>Tiendas / Sucursales</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/puestos') ?>" class="nav-link <?= ($currentController === 'Puestos') ? 'active' : '' ?>">
                    <i class="bi bi-briefcase-fill me-2"></i>
                    <span>Puestos de Trabajo</span>
                </a>
            </li>
        </ul>

        <div class="menu-section-label text-uppercase text-white-50 fs-xs fw-semibold px-2 mb-2">Gestión de Personal</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="<?= $this->Url->build('/empleados') ?>" class="nav-link <?= ($currentController === 'Empleados') ? 'active' : '' ?>">
                    <i class="bi bi-people-fill me-2"></i>
                    <span>Empleados</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/logros') ?>" class="nav-link <?= ($currentController === 'Logros') ? 'active' : '' ?>">
                    <i class="bi bi-award-fill me-2"></i>
                    <span>Logros y Méritos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/llamadas-atencion') ?>" class="nav-link <?= ($currentController === 'LlamadasAtencion') ? 'active' : '' ?>">
                    <i class="bi bi-shield-exclamation me-2"></i>
                    <span>Llamadas de Atención</span>
                </a>
            </li>
        </ul>

        <div class="menu-section-label text-uppercase text-white-50 fs-xs fw-semibold px-2 mb-2">Informes y Reportes</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="<?= $this->Url->build('/reportes/empleados') ?>" class="nav-link <?= ($currentController === 'Reportes' && $currentAction === 'empleados') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-person-fill me-2"></i>
                    <span>General de Empleados</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/reportes/salarios-tienda') ?>" class="nav-link <?= ($currentController === 'Reportes' && $currentAction === 'salariosTienda') ? 'active' : '' ?>">
                    <i class="bi bi-cash-stack me-2"></i>
                    <span>Salarios por Tienda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/reportes/logros') ?>" class="nav-link <?= ($currentController === 'Reportes' && $currentAction === 'logros') ? 'active' : '' ?>">
                    <i class="bi bi-stars me-2"></i>
                    <span>Reporte de Logros</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->Url->build('/reportes/llamadas-atencion') ?>" class="nav-link <?= ($currentController === 'Reportes' && $currentAction === 'llamadasAtencion') ? 'active' : '' ?>">
                    <i class="bi bi-journal-x me-2"></i>
                    <span>Reporte Disciplinario</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer mt-auto p-3 border-top border-secondary border-opacity-25">
        <div class="d-flex align-items-center">
            <div class="oracle-status-pill rounded-circle bg-success me-2 pulse-dot"></div>
            <div class="text-truncate">
                <div class="fs-xs text-white-50">Sterenfix RH</div>
                <div class="fs-xs fw-semibold text-white">Sistema En Línea</div>
            </div>
        </div>
    </div>
</aside>
