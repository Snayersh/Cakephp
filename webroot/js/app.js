/**
 * SISTEMA STERENFIX - COMPONENTES INTERACTIVOS JS
 */

// 1. Intercepción Global de Acciones de Eliminación con SweetAlert2 (Fase de Captura)
// Debe registrarse de inmediato para interceptar los 'onclick' inline generados por postLink
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('a.btn-outline-danger, a.btn-danger, [data-confirm], form[action*="delete"] button, button[data-confirm]');
    if (!deleteBtn) return;

    // Prevenir el submit o redirección inmediata del botón
    e.preventDefault();
    e.stopImmediatePropagation();

    const confirmMessage = deleteBtn.getAttribute('data-confirm') ||
                           deleteBtn.getAttribute('data-swal-confirm') ||
                           deleteBtn.getAttribute('title') ||
                           '¿Está seguro de que desea eliminar este registro? Esta acción no se puede deshacer.';

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
            // Caso 1: Formulario postLink de CakePHP con submit por nombre inline (document.post_xyz.submit())
            const onclickAttr = deleteBtn.getAttribute('onclick') || '';
            const match = onclickAttr.match(/document\.([a-zA-Z0-9_]+)\.submit/);
            if (match && match[1]) {
                const targetForm = document.forms[match[1]] || document.querySelector(`form[name="${match[1]}"]`);
                if (targetForm) {
                    targetForm.submit();
                    return;
                }
            }

            // Caso 2: Formulario hermano inmediato anterior
            const prevForm = deleteBtn.previousElementSibling;
            if (prevForm && prevForm.tagName === 'FORM') {
                prevForm.submit();
                return;
            }

            // Caso 3: Dentro de un formulario
            const parentForm = deleteBtn.closest('form');
            if (parentForm) {
                parentForm.submit();
                return;
            }

            // Caso 4: Enlace tradicional
            if (deleteBtn.href && !deleteBtn.href.startsWith('#') && !deleteBtn.href.startsWith('javascript:')) {
                window.location.href = deleteBtn.href;
            }
        }
    });
}, true); // <- Fase de captura: se ejecuta antes de cualquier onclick inline

document.addEventListener('DOMContentLoaded', function () {
    // 2. Manejo del Sidebar en dispositivos móviles
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

    // 3. Integración con SweetAlert2 para Alertas Flash Automáticas
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

    // 4. Tom Select: Dropdowns Búsqueda Escribible + Lista Completa Siempre Accesible
    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('select:not(.no-tom-select)').forEach(function (selectEl) {
            if (selectEl.tomselect) return;

            const isFilterForm = selectEl.closest('form[method="get"]');
            const placeholder = selectEl.getAttribute('placeholder') || 
                                (selectEl.options[0] ? selectEl.options[0].text : 'Seleccione o escriba para buscar...');

            const ts = new TomSelect(selectEl, {
                create: false,
                allowEmptyOption: true,
                maxOptions: 200,
                placeholder: placeholder,
                dropdownParent: 'body',
                plugins: ['clear_button', 'no_backspace_delete'],
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results p-2 text-muted fs-xs">No hay coincidencias para "' + escape(data.input) + '"</div>';
                    }
                },
                onChange: function(value) {
                    // Filtrado dinámico automático al seleccionar opción
                    if (isFilterForm) {
                        isFilterForm.submit();
                    }
                }
            });

            // Gestión dinámica de texto por defecto vs búsqueda escribible:
            // Al escribir cualquier carácter, el texto por defecto / selección actual se borra/oculta de inmediato.
            // Si el usuario borra lo que escribió (no pone nada), el texto por defecto se restaura automáticamente.
            const syncItemVisibility = function () {
                const query = (ts.control_input.value || '').trim();
                const items = ts.control.querySelectorAll('.item');
                if (query.length > 0) {
                    items.forEach(function (el) { el.style.display = 'none'; });
                } else {
                    items.forEach(function (el) { el.style.display = ''; });
                }
            };

            ts.control_input.addEventListener('input', syncItemVisibility);
            ts.on('type', syncItemVisibility);
            ts.on('blur', function () {
                ts.control.querySelectorAll('.item').forEach(function (el) { el.style.display = ''; });
            });
            ts.on('change', function () {
                ts.control.querySelectorAll('.item').forEach(function (el) { el.style.display = ''; });
            });
            ts.on('clear', function () {
                ts.control.querySelectorAll('.item').forEach(function (el) { el.style.display = ''; });
            });
        });
    }

    // 5. Filtrado Dinámico Automático en Búsqueda por Texto (Debounce 450ms)
    document.querySelectorAll('form[method="get"] input[name="search"]').forEach(function (searchInput) {
        let timer = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                searchInput.form.submit();
            }, 450);
        });
    });

    // 6. Modal Visualizador de Fotografía al hacer clic en miniatura
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

    // 7. Vista Previa en Vivo de Fotografía al Seleccionar Archivo
    const fileInput = document.getElementById('fotografia-file-input');
    const imageLivePreview = document.getElementById('fotografia-live-preview');
    if (fileInput && imageLivePreview) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const validExtensions = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validExtensions.includes(file.type)) {
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
