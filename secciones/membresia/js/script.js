// Funcionalidades específicas para la sección de membresía

// Funciones para los botones de acción
function editBank(bankId) {
    const input = document.querySelector(`input[name="${bankId}"]`);
    if (input) {
        input.focus();
        input.select();
        // Agregar efecto visual
        input.style.borderColor = '#0099ff';
        input.style.boxShadow = '0 0 8px rgba(0, 153, 255, 0.5)';
        
        // Remover efecto después de 2 segundos
        setTimeout(() => {
            input.style.borderColor = '';
            input.style.boxShadow = '';
        }, 2000);
    }
}

function viewBank(bankId) {
    const input = document.querySelector(`input[name="${bankId}"]`);
    if (input) {
        const value = input.value;
        showNotification(`Valor actual de ${bankId}: ${value}%`, 'info');
    }
}

function deleteBank(bankId) {
    if (confirm('¿Estás seguro de que quieres eliminar este banco?')) {
        const input = document.querySelector(`input[name="${bankId}"]`);
        if (input) {
            input.value = '0';
            showNotification(`Banco ${bankId} eliminado`, 'success');
        }
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const membershipForm = document.getElementById('membershipForm');
    if (membershipForm) {
        membershipForm.addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.percentage-input');
            let total = 0;
            
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            if (total > 100) {
                e.preventDefault();
                showNotification('El total de porcentajes no puede exceder el 100%', 'error');
                return false;
            }
            
            if (total < 0) {
                e.preventDefault();
                showNotification('Los porcentajes no pueden ser negativos', 'error');
                return false;
            }
            
            showNotification('Formulario enviado correctamente', 'success');
        });
    }

    // Actualizar total en tiempo real
    const inputs = document.querySelectorAll('.percentage-input');
    inputs.forEach(input => {
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', validateInput);
    });

    // Mostrar total inicial
    updateTotal();
});

// Función para actualizar el total
function updateTotal() {
    const inputs = document.querySelectorAll('.percentage-input');
    let total = 0;
    
    inputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });
    
    // Mostrar el total en algún lugar si es necesario
    const totalDisplay = document.getElementById('total-display');
    if (totalDisplay) {
        totalDisplay.textContent = `Total: ${total}%`;
        
        // Cambiar color según el total
        if (total > 100) {
            totalDisplay.style.color = '#dc3545';
        } else if (total === 100) {
            totalDisplay.style.color = '#28a745';
        } else {
            totalDisplay.style.color = '#6c757d';
        }
    }
}

// Función para validar entrada individual
function validateInput() {
    const value = parseInt(this.value) || 0;
    
    if (value < 0) {
        this.value = 0;
        showNotification('El porcentaje no puede ser negativo', 'error');
    } else if (value > 100) {
        this.value = 100;
        showNotification('El porcentaje no puede exceder 100%', 'error');
    }
    
    updateTotal();
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Estilos de la notificación
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
    `;
    
    // Colores según el tipo
    switch(type) {
        case 'success':
            notification.style.backgroundColor = '#28a745';
            break;
        case 'error':
            notification.style.backgroundColor = '#dc3545';
            break;
        case 'warning':
            notification.style.backgroundColor = '#ffc107';
            notification.style.color = '#212529';
            break;
        default:
            notification.style.backgroundColor = '#0099ff';
    }
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Agregar estilos CSS para las animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Función para guardar datos temporalmente en sessionStorage
function saveTempData() {
    const inputs = document.querySelectorAll('.percentage-input');
    const tempData = {};
    
    inputs.forEach(input => {
        tempData[input.name] = input.value;
    });
    
    sessionStorage.setItem('membresia_temp', JSON.stringify(tempData));
}

// Función para cargar datos temporales
function loadTempData() {
    const tempData = sessionStorage.getItem('membresia_temp');
    if (tempData) {
        const data = JSON.parse(tempData);
        Object.keys(data).forEach(key => {
            const input = document.querySelector(`input[name="${key}"]`);
            if (input) {
                input.value = data[key];
            }
        });
        updateTotal();
    }
}

// Cargar datos temporales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadTempData();
    
    // Guardar datos al cambiar inputs
    const inputs = document.querySelectorAll('.percentage-input');
    inputs.forEach(input => {
        input.addEventListener('input', saveTempData);
    });
});
