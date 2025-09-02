// Guardar datos temporalmente
document.getElementById('formVehiculo').addEventListener('submit', function (e) {
  const formData = new FormData(this);
  const vehiculoData = {
    placa: formData.get('placa'),
    modelo: formData.get('modelo'),
    marca: formData.get('marca'),
    color: formData.get('color'),
    tipo_vehiculo: formData.get('tipo_vehiculo'),
    membresia: formData.get('membresia')
  };
  sessionStorage.setItem('vehiculo_temp', JSON.stringify(vehiculoData));
});

window.addEventListener('beforeunload', function (e) {
  sessionStorage.removeItem('vehiculo_temp');
});

// Cargar datos al volver atrás
document.addEventListener('DOMContentLoaded', function () {
  const vehiculoTemp = sessionStorage.getItem('vehiculo_temp');
  if (vehiculoTemp) {
    const data = JSON.parse(vehiculoTemp);
    document.getElementById('placa').value = data.placa || '';
    document.getElementById('modelo').value = data.modelo || '';
    document.getElementById('marca').value = data.marca || '';
    document.getElementById('color').value = data.color || '';
    document.getElementById('tipo_vehiculo').value = data.tipo_vehiculo || '';
    document.getElementById('membresia').value = data.membresia || '';
  }
});