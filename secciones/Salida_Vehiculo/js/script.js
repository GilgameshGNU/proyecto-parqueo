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
      // Quitar selección anterior
      vehiculos.forEach((el) => el.classList.remove("seleccionado"));

      // Marcar nuevo seleccionado
      v.classList.add("seleccionado");
      seleccionado = v;
      infoSalida.style.display = "block";

      // Obtener datos del vehículo
      const horaEntrada = new Date(v.dataset.horaentrada);
      const salida = new Date();

      // Fecha completa para enviar
      fechaHoraSalida = salida.toISOString().slice(0, 16).replace("T", ":");

      // Solo hora para mostrar
      const horaFormateada = salida.toTimeString().slice(0, 5);

      const descuento = parseFloat(v.dataset.descuento) || 0;

      // Calcular tiempo estacionado
      const diffMs = salida - horaEntrada;
      const diffMins = Math.floor(diffMs / 60000);
      const horas = Math.floor(diffMins / 60);
      const minutos = diffMins % 60;

      // Calcular costo (basado en el tipo de vehículo y tarifas)
      let tarifaPorHora = v.dataset.tarifa;
      let costo = (horas + (minutos > 0 ? 1 : 0)) * tarifaPorHora;

      if (descuento > 0) {
        costo = costo * (1 - descuento / 100);
      }

      // Mostrar solo hora al usuario
      document.getElementById("horaSalida").value = horaFormateada;
      document.getElementById("descuento").value =
        descuento > 0 ? `${descuento}%` : "No aplica";
      document.getElementById("tiempoEstacionado").value = `${horas}h ${minutos}m`;
      document.getElementById("costoTotal").value = `$${costo.toFixed(2)}`;
    });
  });

  document.getElementById("Back").addEventListener("click",() => {
    window.location.href = `../../../index.php`;
  })
  // Confirmar salida
  document.getElementById("confirmarBtn").addEventListener("click", () => {
    if (!seleccionado) {
      alert("Selecciona un vehículo primero");
      return;
    }

    // Antes de enviar/redirigir, poner el valor completo en el input
    document.getElementById("horaSalida").value = fechaHoraSalida;

    const idTicket = seleccionado.dataset.idticket;
    const costo = document.getElementById("costoTotal").value.replace("$", "");
    const horaSalida = document.getElementById("horaSalida").value;

    window.location.href =
      `../../pago/index.php?id_ticket=${idTicket}&costo=${costo}&horasalida=${horaSalida}`;
  });
});
