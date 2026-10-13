/**
 * SISTEMA STERENFIX - COMPONENTES INTERACTIVOS JS
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Manejo del Sidebar en dispositivos móviles
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', function () {
            sidebar.classList.remove('show');
        });
    }

    // 2. Integración con SweetAlert2 para Alertas Flash Automáticas
    const flashAlerts = document.querySelectorAll('[data-flash]');
    flashAlerts.forEach(function (alertEl) {
        const type = alertEl.getAttribute('data-flash');
        const text = alertEl.querySelector('.flex-grow-1') ? alertEl.querySelector('.flex-grow-1').textContent.trim() : alertEl.textContent.trim();
        
        let icon = 'info';
        let title = 'Notificación';
        if (type === 'success') {
            icon = 'success';
            title = '¡Operación Exitosa!';
        } else if (type === 'error') {
            icon = 'error';
            title = 'Atención';
        } else if (type === 'warning') {
            icon = 'warning';
            title = 'Advertencia';
        }

        // Mostrar SweetAlert Toast elegante
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true
        });
    });

    // 3. Confirmación SweetAlert2 para Eliminaciones y Acciones Críticas
    document.querySelectorAll('.form-delete, [data-confirm]').forEach(function (element) {
        element.addEventListener('click', function (e) {
            const confirmMessage = element.getAttribute('data-confirm') || '¿Está seguro de eliminar este registro? Esta acción no se puede deshacer.';
            e.preventDefault();

            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: confirmMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (element.tagName === 'FORM') {
                        element.submit();
                    } else if (element.tagName === 'A' || element.tagName === 'BUTTON') {
                        const form = element.closest('form');
                        if (form) {
                            form.submit();
                        } else if (element.getAttribute('href')) {
                            window.location.href = element.getAttribute('href');
                        }
                    }
                }
            });
        });
    });

    // 4. Modal Visualizador de Fotografía al hacer clic en miniatura
    const modalImage = document.getElementById('modalPreviewImage');
    const modalCaption = document.getElementById('modalPreviewCaption');
    const imageModalEl = document.getElementById('imagePreviewModal');
    let bsImageModal = null;
    if (imageModalEl) {
        bsImageModal = new bootstrap.Modal(imageModalEl);
    }

    document.querySelectorAll('.avatar-thumbnail, .preview-photo-trigger').forEach(function (img) {
        img.addEventListener('click', function (e) {
            e.stopPropagation();
            const fullSrc = img.getAttribute('data-full-src') || img.getAttribute('src');
            const caption = img.getAttribute('data-caption') || img.getAttribute('alt') || 'Fotografía de Empleado';
            
            if (modalImage && bsImageModal) {
                modalImage.src = fullSrc;
                if (modalCaption) {
                    modalCaption.textContent = caption;
                }
                bsImageModal.show();
            }
        });
    });

    // 5. Vista Previa en Vivo de Carga de Fotografía en Formulario
    const fileInput = document.getElementById('fotografia-file-input');
    const imageLivePreview = document.getElementById('fotografia-live-preview');
    if (fileInput && imageLivePreview) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Validación en frontend de tipo y tamaño
                const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato inválido',
                        text: 'Por favor seleccione una imagen en formato JPG, PNG o WEBP.',
                    });
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado pesado',
                        text: 'El tamaño máximo permitido para la fotografía es de 5 MB.',
                    });
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    imageLivePreview.src = e.target.result;
                    imageLivePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
