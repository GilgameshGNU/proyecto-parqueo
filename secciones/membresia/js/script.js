// Variables globales
let bancoEditando = null;

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.innerHTML = `
        <div class="notificacion-contenido">
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${mensaje}</span>
        </div>
    `;
    
    // Agregar estilos
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${tipo === 'success' ? '#d4edda' : tipo === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${tipo === 'success' ? '#155724' : tipo === 'error' ? '#721c24' : '#0c5460'};
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        border: 1px solid ${tipo === 'success' ? '#c3e6cb' : tipo === 'error' ? '#f5c6cb' : '#bee5eb'};
    `;
    
    document.body.appendChild(notificacion);
    
    // Animar entrada
    setTimeout(() => {
        notificacion.style.transform = 'translateX(0)';
    }, 100);
    
    // Remover después de 4 segundos
    setTimeout(() => {
        notificacion.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notificacion);
        }, 300);
    }, 4000);
}

// Función para actualizar el total de porcentajes
function actualizarTotal() {
    const inputs = document.querySelectorAll('.percentage-input');
    let total = 0;
    
    inputs.forEach(input => {
        const valor = parseFloat(input.value) || 0;
        total += valor;
    });
    
    const totalDisplay = document.getElementById('total-display');
    if (totalDisplay) {
        totalDisplay.textContent = `Total: ${total.toFixed(2)}%`;
        
        // Cambiar color según el total
        if (total > 100) {
            totalDisplay.style.color = '#dc3545';
            totalDisplay.style.background = '#f8d7da';
            totalDisplay.style.borderColor = '#f5c6cb';
        } else if (total === 100) {
            totalDisplay.style.color = '#155724';
            totalDisplay.style.background = '#d4edda';
            totalDisplay.style.borderColor = '#c3e6cb';
        } else {
            totalDisplay.style.color = '#6c757d';
            totalDisplay.style.background = '#f8f9fa';
            totalDisplay.style.borderColor = '#e9ecef';
        }
    }
}

// Función para validar formulario
function validarFormulario() {
    const inputs = document.querySelectorAll('.percentage-input');
    let total = 0;
    let hayErrores = false;
    
    inputs.forEach(input => {
        const valor = parseFloat(input.value) || 0;
        total += valor;
        
        if (valor < 0) {
            input.style.borderColor = '#dc3545';
            hayErrores = true;
        } else {
            input.style.borderColor = '#dee2e6';
        }
    });
    
    if (total > 100) {
        mostrarNotificacion('El total de porcentajes no puede exceder 100%', 'error');
        hayErrores = true;
    }
    
    return !hayErrores;
}

// Función para mostrar modal de agregar banco
function mostrarModalAgregar() {
    bancoEditando = null;
    document.getElementById('modalTitle').textContent = 'Agregar Nuevo Banco';
    document.getElementById('bancoId').value = '';
    document.getElementById('bancoNombre').value = '';
    document.getElementById('bancoPorcentaje').value = '';
    document.getElementById('bancoModal').style.display = 'block';
}

// Función para mostrar modal de editar banco
function editarBanco(id) {
    bancoEditando = id;
    document.getElementById('modalTitle').textContent = 'Editar Banco';
    
    // Obtener datos del banco
    fetch('banco_operations.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=obtener&id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('bancoId').value = data.data.IdBanco;
            document.getElementById('bancoNombre').value = data.data.Nombre;
            document.getElementById('bancoPorcentaje').value = data.data.Porcentaje;
            document.getElementById('bancoModal').style.display = 'block';
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        mostrarNotificacion('Error al cargar datos del banco', 'error');
    });
}

// Función para ver banco (mostrar detalles)
function verBanco(id) {
    fetch('banco_operations.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=obtener&id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const banco = data.data;
            mostrarNotificacion(`Banco: ${banco.Nombre} - Porcentaje: ${banco.Porcentaje}%`, 'info');
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        mostrarNotificacion('Error al obtener datos del banco', 'error');
    });
}

// Función para eliminar banco
function eliminarBanco(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este banco?')) {
        fetch('banco_operations.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=eliminar&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion(data.message, 'success');
                // Recargar la página para mostrar los cambios
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                mostrarNotificacion(data.message, 'error');
            }
        })
        .catch(error => {
            mostrarNotificacion('Error al eliminar banco', 'error');
        });
    }
}

// Función para cerrar modal
function cerrarModal() {
    document.getElementById('bancoModal').style.display = 'none';
    bancoEditando = null;
}

// Función para guardar banco (agregar o actualizar)
function guardarBanco(formData) {
    const action = bancoEditando ? 'actualizar' : 'agregar';
    
    fetch('banco_operations.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=${action}&${new URLSearchParams(formData).toString()}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion(data.message, 'success');
            cerrarModal();
            // Recargar la página para mostrar los cambios
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        mostrarNotificacion('Error al guardar banco', 'error');
    });
}

// Event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Event listener para inputs de porcentaje
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('percentage-input')) {
            actualizarTotal();
        }
    });
    
    // Event listener para el formulario de banco
    const bancoForm = document.getElementById('bancoForm');
    if (bancoForm) {
        bancoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            guardarBanco(formData);
        });
    }
    
    // Event listener para cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });
    
    // Event listener para cerrar modal haciendo clic fuera
    document.getElementById('bancoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
    
    // Event listener para el formulario principal
    const membershipForm = document.getElementById('membershipForm');
    if (membershipForm) {
        membershipForm.addEventListener('submit', function(e) {
            if (!validarFormulario()) {
                e.preventDefault();
                return false;
            }
            
            // Mostrar mensaje de guardando
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            submitBtn.disabled = true;
            
            // Simular envío (en un caso real, esto se manejaría con AJAX)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                mostrarNotificacion('Cambios guardados exitosamente', 'success');
            }, 2000);
        });
    }
    
    // Inicializar total
    actualizarTotal();
    
    // Guardar datos temporales en sessionStorage
    const inputs = document.querySelectorAll('.percentage-input');
    inputs.forEach(input => {
        const savedValue = sessionStorage.getItem(`banco_${input.dataset.id}`);
        if (savedValue) {
            input.value = savedValue;
            actualizarTotal();
        }
        
        input.addEventListener('input', function() {
            sessionStorage.setItem(`banco_${this.dataset.id}`, this.value);
        });
    });
});

// Función para limpiar datos temporales
function limpiarDatosTemporales() {
    const inputs = document.querySelectorAll('.percentage-input');
    inputs.forEach(input => {
        sessionStorage.removeItem(`banco_${input.dataset.id}`);
    });
}

// Función para cargar datos desde sessionStorage
function cargarDatosTemporales() {
    const inputs = document.querySelectorAll('.percentage-input');
    inputs.forEach(input => {
        const savedValue = sessionStorage.getItem(`banco_${input.dataset.id}`);
        if (savedValue) {
            input.value = savedValue;
        }
    });
    actualizarTotal();
}
