document.addEventListener('DOMContentLoaded', function() {
    // Mostrar QR solo si se selecciona QR, pero los campos NIT y Razon siempre visibles
    const paymentMethods = document.querySelectorAll('input[name="metodo_pago"]');
    const qrSection = document.getElementById('qr-section');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'QR') {
                qrSection.style.display = 'block';
            } else {
                qrSection.style.display = 'none';
            }
        });
    });

    // Validación del formulario
    const paymentForm = document.getElementById('payment-form');
    paymentForm.addEventListener('submit', function(e) {
        // Ya no validamos NIT ni Razón Social
        const btn = this.querySelector('button[type="submit"]');
        btn.textContent = 'Procesando...';
        btn.disabled = true;
    });
});
