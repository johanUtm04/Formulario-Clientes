document.addEventListener('DOMContentLoaded', function() {
    const campoRFC = document.querySelector('input[name="rfc"]');
    if (campoRFC) {
        campoRFC.addEventListener('input', function() {
            // Convierte a mayúsculas y quita espacios
            this.value = this.value.toUpperCase().replace(/\s/g, '');
            
            // Limita a 13 caracteres (Persona Física o Moral)
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
        });
    }

    // 2. Manejo de la Modal de Éxito
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        const modal = document.getElementById('modalExito');
        if (modal) modal.style.display = 'block';
    }
});

function marcarAdjunto(input) {
    if (input.files.length > 0) {
        input.classList.add('archivo-adjuntado');
    } else {
        input.classList.remove('archivo-adjuntado');
    }
}

function cerrarModal() {
    const modal = document.getElementById('modalExito');
    if (modal) {
        modal.style.display = 'none';
        // Esto limpia la URL de "?status=success" sin recargar la página
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}