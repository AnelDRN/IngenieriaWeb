// Se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // --- SELECCIÓN DE ELEMENTOS DEL DOM ---
    const formulario = document.getElementById('formularioProducto');
    const productoIdInput = document.getElementById('productoId');
    const codigoInput = document.getElementById('codigo');
    const productoInput = document.getElementById('producto');
    const precioInput = document.getElementById('precio');
    const cantidadInput = document.getElementById('cantidad');
    
    const btnGuardar = document.getElementById('btnGuardar');
    const btnBuscar = document.getElementById('btnBuscar');
    
    const tablaProductos = document.getElementById('tablaProductos');
    const ENDPOINT = 'registrar.php';

    // --- FUNCIONES AUXILIARES ---

    /**
     * Muestra una alerta utilizando SweetAlert2, siguiendo el formato de la guía.
     * @param {object} data - El objeto de respuesta del servidor.
     */
    const mostrarAlerta = (data) => {
        const alertConfig = {
            icon: data.success ? 'success' : 'error',
            title: data.success ? 'Éxito' : 'Error',
            text: data.message,
        };
        if (!data.success && data.errors && data.errors.length > 0) {
            alertConfig.footer = data.errors.join('<br>');
        }
        Swal.fire(alertConfig);
    };

    /**
     * Limpia el formulario y lo resetea a su estado inicial.
     */
    window.limpiarFormulario = () => {
        formulario.reset();
        productoIdInput.value = '';
        btnGuardar.textContent = 'Registrar';
        codigoInput.disabled = false;
    };

    /**
     * Valida los campos del formulario en el lado del cliente.
     * @returns {boolean} True si el formulario es válido, false en caso contrario.
     */
    const validarFormularioCliente = () => {
        if (!codigoInput.value || !productoInput.value || !precioInput.value || !cantidadInput.value) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Todos los campos son obligatorios.' });
            return false;
        }
        if (parseFloat(precioInput.value) < 0) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'El precio no puede ser negativo.' });
            return false;
        }
        if (parseInt(cantidadInput.value) < 0) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'La cantidad no puede ser negativa.' });
            return false;
        }
        return true;
    };

    /**
     * Procesa la respuesta del servidor y actúa en consecuencia.
     * @param {object} data - La respuesta JSON del servidor.
     */
    const procesarRespuesta = (data) => {
        mostrarAlerta(data);

        if (data.success) {
            switch (data.accion) {
                case 'Guardar':
                case 'Modificar':
                    limpiarFormulario();
                    listarProductos();
                    break;
                case 'Buscar':
                    if (data.data) {
                        // Llenar el formulario con los datos encontrados
                        productoIdInput.value = data.data.id;
                        codigoInput.value = data.data.codigo;
                        productoInput.value = data.data.producto;
                        precioInput.value = data.data.precio;
                        cantidadInput.value = data.data.cantidad;
                        
                        // Cambiar estado a modo "Modificar"
                        btnGuardar.textContent = 'Actualizar';
                        codigoInput.disabled = true; // El código no se puede editar
                    }
                    break;
            }
        }
    };

    /**
     * Envía la petición al servidor usando Fetch API.
     * @param {string} accion - La acción a realizar ('Guardar', 'Modificar', 'Buscar').
     */
    const enviarPeticion = async (accion) => {
        const formData = new FormData(formulario);
        formData.append('Accion', accion);

        // Para 'Modificar', nos aseguramos de que el 'codigo' (deshabilitado) se envíe
        if (accion === 'Modificar') {
            formData.set('codigo', codigoInput.value);
        }

        try {
            const response = await fetch(ENDPOINT, { method: 'POST', body: formData });
            if (!response.ok) {
                throw new Error(`Error de red: ${response.statusText}`);
            }
            const data = await response.json();
            procesarRespuesta(data);
        } catch (error) {
            Swal.fire('Error', `Ocurrió un problema de conexión: ${error.message}`, 'error');
        }
    };

    /**
     * Obtiene y muestra todos los productos en la tabla.
     */
    const listarProductos = async () => {
        try {
            const response = await fetch(`${ENDPOINT}?accion=Listar`);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            
            const result = await response.json();

            tablaProductos.innerHTML = ''; // Limpiar tabla
            if (result.success && result.data.length > 0) {
                result.data.forEach(p => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${p.id}</td>
                        <td>${p.codigo}</td>
                        <td>${p.producto}</td>
                        <td>${parseFloat(p.precio).toFixed(2)}</td>
                        <td>${p.cantidad}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btn-editar" data-codigo="${p.codigo}">Editar</button>
                        </td>
                    `;
                    tablaProductos.appendChild(fila);
                });
            } else {
                tablaProductos.innerHTML = '<tr><td colspan="6" class="text-center">No hay productos registrados.</td></tr>';
            }
        } catch (error) {
            Swal.fire('Error', `No se pudieron cargar los productos: ${error.message}`, 'error');
        }
    };

    // --- ASIGNACIÓN DE EVENT LISTENERS ---

    // Enviar formulario para Guardar o Modificar
    formulario.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!validarFormularioCliente()) return;
        
        const accion = productoIdInput.value ? 'Modificar' : 'Guardar';
        enviarPeticion(accion);
    });

    // Buscar producto por código
    btnBuscar.addEventListener('click', () => {
        if (!codigoInput.value) {
            Swal.fire('Información', 'Por favor, ingrese un código para buscar.', 'info');
            return;
        }
        // Usamos un FormData temporal para la búsqueda
        const formData = new FormData();
        formData.append('codigo', codigoInput.value);
        
        const tempForm = document.createElement('form');
        tempForm.appendChild(codigoInput.cloneNode());
        
        enviarPeticion('Buscar');
    });

    // Clic en botón "Editar" de la tabla
    tablaProductos.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-editar')) {
            const codigo = e.target.dataset.codigo;
            
            // Rellenamos el campo código y disparamos la búsqueda
            codigoInput.value = codigo;
            enviarPeticion('Buscar');
            
            // Mover la vista al formulario
            window.scrollTo(0, 0);
        }
    });

    // --- INICIALIZACIÓN ---
    listarProductos();
});