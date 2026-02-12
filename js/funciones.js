    /**
     * PIHCSA - Funciones de Control de Formulario y Modales
     */

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Elementos para validación de Privacidad y Firma
        const checkbox = document.getElementById('checkPrivacidad');
        const inputFirma = document.getElementById('inputFirma');
        const boton = document.getElementById('btnFinalizar');

        function validarAceptacion() {
            const nombreValido = inputFirma.value.trim().length > 3; 
            const checkAceptado = checkbox.checked;

            if (checkAceptado && nombreValido) {
                boton.disabled = false;
                boton.classList.remove('btn_deshabilitado');
            } else {
                boton.disabled = true;
                boton.classList.add('btn_deshabilitado');
            }
        }

        if (checkbox && inputFirma) {
            checkbox.addEventListener('change', validarAceptacion);
            inputFirma.addEventListener('input', validarAceptacion);
        }

        // 2. Formateo de RFC (Mayúsculas y límite de caracteres)
        const campoRFC = document.querySelector('input[name="rfc"]');
        if (campoRFC) {
            campoRFC.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/\s/g, '');
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13);
                }
            });
        }

        // 3. Manejo de Notificaciones por URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'rfc_existente') {
            const aviso = document.createElement('div');
            aviso.innerHTML = `
                <div id="alerta-rfc" style="background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px auto; max-width: 800px; text-align: center; font-family: Arial, sans-serif; position: relative; z-index: 1000; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <span onclick="this.parentElement.remove()" style="position: absolute; right: 15px; top: 10px; cursor: pointer; font-size: 20px; font-weight: bold;">&times;</span>
                    <strong>⚠️ RFC YA REGISTRADO:</strong> El RFC ya cuenta con un expediente en PIHCSA.
                </div>`;
            document.body.prepend(aviso);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        if (urlParams.get('status') === 'success') {
            const modalExito = document.getElementById('modalExito');
            if (modalExito) modalExito.style.display = 'block';
        }

        // INICIALIZACIÓN: Bloquear Aviso RS al cargar si no hay licencia
        const inLic = document.getElementById('file_licencia');
        const inAvi = document.getElementById('file_aviso_rs');
        if(inLic && inAvi && inLic.files.length === 0) {
            inAvi.disabled = true;
            inAvi.style.opacity = "0.5";
            inAvi.style.filter = "grayscale(100%)";
            inAvi.style.cursor = "not-allowed";
        }
    });

    /**
     * Funciones Globales
     */

    function cerrarModal() {
        const modal = document.getElementById('modalExito');
        if (modal) {
            modal.style.display = 'none';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
    function marcarAdjunto(input) {
        const inAvi = document.getElementById('file_aviso_rs');
        if (!inAvi) return;

        if (input.files.length > 0) {
            input.style.borderColor = "#28a745";
            input.classList.add('archivo-adjuntado');

            if (input.id === 'file_licencia') {
                inAvi.disabled = false;
                inAvi.style.opacity = "1";
                inAvi.style.filter = "none"; 
                inAvi.style.cursor = "pointer";
                if(inAvi.parentElement) inAvi.parentElement.style.opacity = "1";
            }
        } else {
            input.style.borderColor = "";
            input.classList.remove('archivo-adjuntado');

            if (input.id === 'file_licencia') {
                inAvi.disabled = true;
                inAvi.value = ""; 
                inAvi.style.opacity = "0.4";
                inAvi.style.filter = "grayscale(100%)";
                inAvi.style.cursor = "not-allowed";
                if(inAvi.parentElement) inAvi.parentElement.style.opacity = "0.5";
            }
        }
    }

    function seleccionarTipoLegal(tipo) {
        const btnLic = document.getElementById('btnOpcionLicencia');
        const btnFun = document.getElementById('btnOpcionFuncionamiento');
        const divLic = document.getElementById('campos_licencia');
        const divFun = document.getElementById('campos_funcionamiento');
        const inLic = document.getElementById('file_licencia');
        const inAvi = document.getElementById('file_aviso_rs');
        const inFun = document.getElementById('file_funcionamiento');

        if (tipo === 'licencia') {
            btnLic.style.background = '#005596'; btnLic.style.color = 'white';
            btnFun.style.background = 'white'; btnFun.style.color = '#005596';
            divLic.style.display = 'block';
            divFun.style.display = 'none';
            
            inLic.required = true; inAvi.required = true; inFun.required = false; 

            if (inLic.files.length === 0) {
                inAvi.disabled = true;
                inAvi.style.opacity = "0.4";
                inAvi.style.filter = "grayscale(100%)";
                inAvi.style.cursor = "not-allowed";
            }

            if (inFun.value !== "") {
                alert("Se ha descartado el Aviso de Funcionamiento seleccionado anteriormente.");
                inFun.value = ""; inFun.style.borderColor = "";
                inFun.classList.remove('archivo-adjuntado');
            }
        } else {
            btnFun.style.background = '#005596'; btnFun.style.color = 'white';
            btnLic.style.background = 'white'; btnLic.style.color = '#005596';
            divLic.style.display = 'none';
            divFun.style.display = 'block';
            
            inLic.required = false; inAvi.required = false; inFun.required = true;

            if (inLic.value !== "" || inAvi.value !== "") {
                alert("Se han descartado los archivos de Licencia y Aviso RS anteriores.");
                inLic.value = ""; inAvi.value = "";
                inLic.classList.remove('archivo-adjuntado');
                inAvi.classList.remove('archivo-adjuntado');
                inLic.style.borderColor = ""; inAvi.style.borderColor = "";
            }
        }
    }