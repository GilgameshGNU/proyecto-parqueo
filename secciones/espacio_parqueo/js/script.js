function crearEspacios(contenedorId, filas, columnas) {
    const contenedor = document.getElementById(contenedorId);
    for (let i = 0; i < filas * columnas; i++) {
        const div = document.createElement("div");
        div.classList.add("espacio");
        // Asignar identificador único
        div.id = `${contenedorId}-espacio-${i + 1}`;
        contenedor.appendChild(div);
    }

    document.querySelectorAll('.espacio').forEach(div => {
        div.addEventListener("click", () => {
            div.classList.toggle("ocupado");
            // Aquí puedes agregar lógica para guardar el cambio en la BD si lo necesitas
        });
    });
}

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
      div.id = (espacio.Zona === 'Planta alta' ? 'planta-alta' : 'planta-baja') + '-espacio-' + espacio.NumeroEspacio;
      // Evento para cambiar color al hacer clic
      div.addEventListener('click', function() {
        div.classList.toggle('ocupado');
        // Aquí puedes agregar lógica para actualizar el estado en la base de datos con fetch/AJAX si lo necesitas
      });
      if (espacio.Zona === 'Planta alta') alta.appendChild(div);
      else baja.appendChild(div);
    });
  });

