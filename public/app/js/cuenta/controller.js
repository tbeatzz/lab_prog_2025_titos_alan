import { cuentaService } from './service.js';

document.addEventListener('DOMContentLoaded', async () => {
    const form = document.getElementById('perfilForm');
    const btnEditar = document.getElementById('btnEditar');
    const btnGuardar = document.getElementById('btnGuardar');
    const btnCancelar = document.getElementById('btnCancelar');

    const clave = document.getElementById('clave');
    const confirmarClave = document.getElementById('confirmarClave');
    
    const userFecha= document.getElementById('userFecha');

    const apellidos = document.getElementById('apellidos');
    const nombres = document.getElementById('nombres');
    const cuenta = document.getElementById('cuenta');
    const perfil = document.getElementById('perfil');
    const correo = document.getElementById('correo');

    let perfilUsuario = '';

    try {
        const response = await cuentaService.loadMisDatos();
        const result = response.result;

        apellidos.value = result.apellido || '';
        nombres.value = result.nombres || '';
        cuenta.value = result.cuenta || '';
        perfil.value = result.perfil || '';
        correo.value = result.correo || '';
        
        userFecha.innerHTML = `<strong>Fecha de creación:</strong> ${ result.fechaAlta|| 'N/A'}`;
        perfilUsuario = (result.perfil || '').toLowerCase();
        
    } catch (error) {
        console.error('Error al cargar los datos:', error);
        alert('Error al cargar tus datos.');
    }

    btnEditar.addEventListener('click', () => {
        if (perfilUsuario === 'administrador') {
            apellidos.disabled = false;
            nombres.disabled = false;
            cuenta.disabled = false;
            perfil.disabled = false;
            correo.disabled = false;
        }
        clave.disabled = false;
        confirmarClave.disabled = false;

        btnGuardar.classList.remove('d-none');
        btnCancelar.classList.remove('d-none');
        btnEditar.classList.add('d-none');
    });

    btnCancelar.addEventListener('click', () => {
        clave.value = '';
        confirmarClave.value = '';
        clave.disabled = true;
        confirmarClave.disabled = true;

        if (perfilUsuario === 'administrador') {
            apellidos.disabled = true;
            nombres.disabled = true;
            cuenta.disabled = true;
            perfil.disabled = true;
            correo.disabled = true;
        }

        btnGuardar.classList.add('d-none');
        btnCancelar.classList.add('d-none');
        btnEditar.classList.remove('d-none');
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const quiereCambiarClave = clave.value.trim() !== '' || confirmarClave.value.trim() !== '';

        if (quiereCambiarClave) {
            if (clave.value.trim() === '' || confirmarClave.value.trim() === '') {
                alert('Debe completar ambos campos de contraseña.');
                return;
            }
            if (clave.value !== confirmarClave.value) {
                alert('Las contraseñas no coinciden.');
                return;
            }
        }

        try {
            // Si es admin, actualizar datos personales (sin clave)
            if (perfilUsuario === 'administrador') {
                const datosActualizados = {
                    apellido: apellidos.value,
                    nombres: nombres.value,
                    cuenta: cuenta.value,
                    perfil: perfil.value,
                    correo: correo.value
                };
                await cuentaService.updateDatos(datosActualizados);
                alert('Datos personales actualizados.');
            }

            // Cambiar clave si hay intención
            if (quiereCambiarClave) {
                const response = await cuentaService.changePassword(clave.value);
                alert(response.message || 'Contraseña actualizada.');

                clave.value = '';
                confirmarClave.value = '';
            }

            // Deshabilitar todos los campos tras guardar
            apellidos.disabled = true;
            nombres.disabled = true;
            cuenta.disabled = true;
            perfil.disabled = true;
            correo.disabled = true;
            clave.disabled = true;
            confirmarClave.disabled = true;

            btnGuardar.classList.add('d-none');
            btnCancelar.classList.add('d-none');
            btnEditar.classList.remove('d-none');

        } catch (error) {
            console.error('Error al actualizar:', error);
            alert('Error al actualizar tus datos.');
        }
    });
});
