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

      let tarifaPorHora = v.dataset.tarifa;
      let costo = (horas + (minutos > 0 ? 1 : 0)) * tarifaPorHora;

      if (descuento > 0) {
        costo = costo * (1 - descuento / 100);
      }

      document.getElementById("horaSalida").value = horaFormateada;
      document.getElementById("descuento").value =
        descuento > 0 ? `${descuento}%` : "No aplica";
      document.getElementById("tiempoEstacionado").value = `${horas}h ${minutos}m`;
      document.getElementById("costoTotal").value = `$${costo.toFixed(2)}`;
    });
  });

  document.getElementById("Back").addEventListener("click", () => {
    window.location.href = `../../../index.php`;
  });

  // Confirmar salida con POST
  document.getElementById("confirmarBtn").addEventListener("click", () => {
    if (!seleccionado) {
      alert("Selecciona un vehículo primero");
      return;
    }

    document.getElementById("horaSalida").value = fechaHoraSalida;

    const idTicket = seleccionado.dataset.idticket;
    const costo = document.getElementById("costoTotal").value.replace("$", "");
    const horaSalida = document.getElementById("horaSalida").value;

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
});
