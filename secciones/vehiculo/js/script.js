document.addEventListener("DOMContentLoaded", () => {
    const buscador = document.getElementById("buscador");
    const filas = document.querySelectorAll("#tablaVehiculos tr");

    buscador.addEventListener("keyup", () => {
        const texto = buscador.value.toLowerCase();
        filas.forEach(fila => {
            fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
        });
    });
});
