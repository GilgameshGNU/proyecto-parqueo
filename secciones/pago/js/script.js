document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar sección de QR según método de pago seleccionado
    const paymentMethods = document.querySelectorAll('input[name="metodo_pago"]');
    const qrSection = document.getElementById('qr-section');
    const nitField = document.getElementById('nit');
    const razonSocialField = document.getElementById('razon_social');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'QR') {
                qrSection.style.display = 'block';
                nitField.setAttribute('required', 'required');
                razonSocialField.setAttribute('required', 'required');
            } else {
                qrSection.style.display = 'none';
                nitField.removeAttribute('required');
                razonSocialField.removeAttribute('required');
            }
        });
    });
    
    // Validación del formulario
    const paymentForm = document.getElementById('payment-form');
    
    paymentForm.addEventListener('submit', function(e) {
        let isValid = true;
        const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
        
        // Validar campos de facturación si se selecciona QR
        if (metodoPago === 'QR') {
            const nit = document.getElementById('nit').value.trim();
            const razonSocial = document.getElementById('razon_social').value.trim();
            
            if (!nit) {
                alert('Por favor ingrese el NIT para el pago con QR');
                isValid = false;
            } else if (!razonSocial) {
                alert('Por favor ingrese la Razón Social para el pago con QR');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        } else {
            // Simular procesamiento de pago
            const btn = this.querySelector('button[type="submit"]');
            btn.textContent = 'Procesando...';
            btn.disabled = true;
        }
    });
});