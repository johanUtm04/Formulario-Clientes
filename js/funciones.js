
const campoRFC = document.querySelector('input[name="rfc"]');

campoRFC.addEventListener('input', function() {

    this.value = this.value.toUpperCase().replace(/\s/g, '');
    
    if (this.value.length > 13) {
        this.value = this.value.slice(0, 13);
    }
});
