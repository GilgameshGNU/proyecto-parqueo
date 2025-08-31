  // Guardar en sessionStorage mientras se completa
  document.getElementById('formCliente').addEventListener('submit', function(e) {
    const formData = new FormData(this);
    const clienteData = {
      nombre: formData.get('nombre'),
      telefono: formData.get('telefono'),
      ci: formData.get('ci')
    };
    sessionStorage.setItem('cliente_temp', JSON.stringify(clienteData));
  });

  // Cargar datos al volver atrás
  document.addEventListener('DOMContentLoaded', function() {
    const clienteTemp = sessionStorage.getItem('cliente_temp');
    if (clienteTemp) {
      const data = JSON.parse(clienteTemp);
      document.getElementById('nombre').value = data.nombre || '';
      document.getElementById('telefono').value = data.telefono || '';
      document.getElementById('ci').value = data.ci || '';
    }
  });