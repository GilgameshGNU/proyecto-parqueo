document.addEventListener("DOMContentLoaded", () => {
  const vehiculos = document.querySelectorAll(".vehiculo-item");
  const infoSalida = document.getElementById("infoSalida");
  const searchInput = document.getElementById("searchInput");
  let seleccionado = null;
  let fechaHoraSalida = "";

  // Función para buscar vehículos
  searchInput.addEventListener("input", () => {
    const searchText = searchInput.value.toLowerCase();

    vehiculos.forEach((vehiculo) => {
      const placa = vehiculo.dataset.placa.toLowerCase();
      const modelo = vehiculo.dataset.modelo.toLowerCase();
      const marca = vehiculo.dataset.marca.toLowerCase();
      const zona = vehiculo.dataset.zona.toLowerCase();

      if (
        placa.includes(searchText) ||
        modelo.includes(searchText) ||
        marca.includes(searchText) ||
        zona.includes(searchText)
      ) {
        vehiculo.style.display = "flex";
      } else {
        vehiculo.style.display = "none";
      }
    });
  });

  // Selección de vehículo
  vehiculos.forEach((v) => {
    v.addEventListener("click", () => {
      vehiculos.forEach((el) => el.classList.remove("seleccionado"));

      v.classList.add("seleccionado");
      seleccionado = v;
      infoSalida.style.display = "block";

      const horaEntrada = new Date(v.dataset.horaentrada);
      const salida = new Date();

      fechaHoraSalida = salida.toISOString().slice(0, 16).replace("T", ":");

      const horaFormateada = salida.toTimeString().slice(0, 5);

      const descuento = parseFloat(v.dataset.descuento) || 0;

      const diffMs = salida - horaEntrada;
      const diffMins = Math.floor(diffMs / 60000);
      const horas = Math.floor(diffMins / 60);
      const minutos = diffMins % 60;

      // Usar el costo total calculado en PHP
      let costo = parseFloat(v.dataset.costoTotal) || 0;

      document.getElementById("horaSalida").value = horaFormateada;
      document.getElementById("tarifaHora").value = `Bs ${v.dataset.tarifa}`;
      document.getElementById("descuento").value =
        descuento > 0 ? `${descuento}%` : "No aplica";
      document.getElementById("tiempoEstacionado").value = `${horas}h ${minutos}m`;
      document.getElementById("costoTotal").value = `Bs ${costo.toFixed(2)}`;
    });
  });

  document.getElementById("Back").addEventListener("click", () => {
    window.location.href = `../../../index.php`;
  });

  // Botón para ir a pago después de confirmar salida
  document.getElementById("irPagoBtn").addEventListener("click", () => {
    if (!seleccionado) {
      alert("No hay vehículo seleccionado");
      return;
    }

    const idTicket = seleccionado.dataset.idticket;
    const costo = document.getElementById("costoTotal").value.replace("Bs ", "");
    const horaSalida = fechaHoraSalida;

    // Crear formulario dinámico para enviar por POST
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "../pago/index.php";

    const inputId = document.createElement("input");
    inputId.type = "hidden";
    inputId.name = "id_ticket";
    inputId.value = idTicket;
    form.appendChild(inputId);

    const inputCosto = document.createElement("input");
    inputCosto.type = "hidden";
    inputCosto.name = "costo";
    inputCosto.value = costo;
    form.appendChild(inputCosto);

    const inputHora = document.createElement("input");
    inputHora.type = "hidden";
    inputHora.name = "horasalida";
    inputHora.value = horaSalida;
    form.appendChild(inputHora);

    document.body.appendChild(form);
    form.submit();
  });

  // Confirmar salida con POST
  document.getElementById("confirmarBtn").addEventListener("click", () => {
    if (!seleccionado) {
      alert("Selecciona un vehículo primero");
      return;
    }

    const idTicket = seleccionado.dataset.idticket;
    const horaSalida = fechaHoraSalida;

    // Procesar la salida inmediatamente
    procesarSalida(idTicket, horaSalida);
  });

  // Función para procesar la salida del vehículo
  async function procesarSalida(idTicket, horaSalida) {
    try {
      const formData = new FormData();
      formData.append('id_ticket', idTicket);
      formData.append('horasalida', horaSalida);

      const response = await fetch('procesar_salida.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        // Mostrar mensaje de éxito
        alert(`Vehículo salió exitosamente. Espacio ${result.espacio} (${result.zona}) liberado.`);
        
        // Cambiar botón para ir a pago
        document.getElementById('confirmarBtn').style.display = 'none';
        document.getElementById('irPagoBtn').style.display = 'block';
        
        // Mostrar mensaje de éxito en la interfaz
        const infoSalida = document.getElementById('infoSalida');
        const mensajeExito = document.createElement('div');
        mensajeExito.className = 'mensaje-exito';
        mensajeExito.innerHTML = `<p style="color: green; font-weight: bold;">✓ ${result.message}</p>`;
        infoSalida.appendChild(mensajeExito);
        
      } else {
        alert('Error: ' + result.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error al procesar la salida del vehículo');
    }
  }
});
