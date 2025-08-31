fetch('detalle.php')
  .then(response => response.json())
  .then(data => {
    const alta = document.getElementById('planta-alta');
    const baja = document.getElementById('planta-baja');
    alta.innerHTML = '';
    baja.innerHTML = '';
    
    data.forEach(espacio => {
      const div = document.createElement('div');
      div.className = 'espacio' + (espacio.Estado !== 'Disponible' ? ' ocupado' : '');
      div.textContent = espacio.NumeroEspacio;
      div.id = 'espacio-' + espacio.IdEspacioParqueo;
      div.dataset.id = espacio.IdEspacioParqueo;
      div.dataset.estado = espacio.Estado;

      // SOLO permitir clic en espacios disponibles
      if (espacio.Estado === 'Disponible') {
        div.addEventListener('click', function() {
          // Marcar como seleccionado
          document.querySelectorAll('.espacio.seleccionado').forEach(el => {
            el.classList.remove('seleccionado');
          });
          div.classList.add('seleccionado');
          
          // Guardar el espacio seleccionado en sessionStorage para usarlo después
          sessionStorage.setItem('espacioSeleccionado', espacio.IdEspacioParqueo);
          sessionStorage.setItem('numeroEspacio', espacio.NumeroEspacio);
        });
      } else {
        div.style.cursor = 'not-allowed'; // Cursor bloqueado para ocupados
      }

      if (espacio.Zona === 'Planta alta') alta.appendChild(div);
      else baja.appendChild(div);
    });
    
    // Agregar evento al botón "Asignar Espacio"
    const btnAsignar = document.getElementById('btnAsignarEspacio');
    if (btnAsignar) {
      btnAsignar.addEventListener('click', function() {
        const idEspacio = sessionStorage.getItem('espacioSeleccionado');
        const numeroEspacio = sessionStorage.getItem('numeroEspacio');
        
        if (idEspacio && numeroEspacio) {
          // Primero guardar el espacio en sesión PHP
          fetch('guardar_espacio_session.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              id_espacio: idEspacio,
              numero_espacio: numeroEspacio
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Luego redirigir a guardar_todo.php
              window.location.href = 'guardar_todo.php';
            } else {
              alert('Error al guardar el espacio: ' + (data.error || 'Error desconocido'));
            }
          })
          .catch(error => {
            alert('Error de conexión: ' + error.message);
          });
        } else {
          alert('Por favor, seleccione un espacio disponible primero');
        }
      });
    }
  });

// Función para actualizar estado en BD (se usa en guardar_todo.php)
// Cuando se asigna un espacio, usar 'Mantenimiento' en lugar de 'Ocupado'
function actualizarEstadoEspacio(idEspacio, nuevoEstado) {
  // Asegurar que solo use los estados permitidos
  const estadosPermitidos = ['Disponible', 'Mantenimiento'];
  if (!estadosPermitidos.includes(nuevoEstado)) {
    console.error('Estado no permitido:', nuevoEstado);
    return Promise.reject('Estado no permitido');
  }
  
  return fetch('actualizar_espacio.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      id: idEspacio,
      estado: nuevoEstado
    })
  });
}