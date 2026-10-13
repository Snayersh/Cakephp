<!DOCTYPE html>
<html lang="es">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?> - Sterenfix Sistema de Gestión de Personal
    </title>
    <meta name="description" content="Sistema Integral de Gestión de Personal, Kárdex 360°, Nómina y Reportes de Sterenfix">
    <?= $this->Html->meta('icon') ?>

    <!-- Fuentes Tipográficas Modernas: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos: Bootstrap Icons & FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css">

    <!-- Estilos Personalizados Sterenfix -->
    <link rel="stylesheet" href="/css/tom-select.bootstrap5.min.css">
    <link rel="stylesheet" href="/css/app.css">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="bg-body-tertiary">
    <div class="app-wrapper d-flex">
        <!-- Barra Lateral de Navegación -->
        <?= $this->element('sidebar') ?>

        <!-- Contenedor Principal de la Aplicación -->
        <div class="main-content flex-grow-1 d-flex flex-column min-vh-100">
            <!-- Barra Superior Navbar -->
            <?= $this->element('navbar') ?>

            <!-- Área Central de Vistas -->
            <main class="page-body container-fluid p-3 p-md-4 flex-grow-1">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </main>

            <!-- Pie de Página Institucional -->
            <footer class="footer bg-white border-top py-3 px-4 mt-auto">
                <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center text-muted fs-xs">
                    <div>
                        <strong>Sterenfix</strong> &copy; <?= date('Y') ?> - Sistema de Gestión de Personal. Todos los derechos reservados.
                    </div>
                    <div class="mt-2 mt-sm-0">
                        
                        <span class="badge bg-light text-secondary border"><i class="fa-brands fa-php me-1"></i>CakePHP MVC</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal Global para Vista Previa de Fotografía en Alta Resolución -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-2">
                    <h5 class="modal-title fs-6" id="imagePreviewModalLabel">
                        <i class="bi bi-image me-2"></i>Visualizador de Fotografía
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center p-4 bg-light">
                    <img src="" id="modalPreviewImage" class="img-fluid rounded-3 shadow border" alt="Fotografía del Empleado" style="max-height: 480px;">
                    <div id="modalPreviewCaption" class="mt-3 fw-semibold text-secondary"></div>
                </div>
                <div class="modal-footer py-2 bg-white">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Librerías JavaScript Base -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <!-- Script de Funcionalidades UI Sterenfix -->
    <script src="/js/tom-select.complete.min.js"></script>
    <script src="/js/app.js"></script>

    <?= $this->fetch('script') ?>
</body>
</html>
