import { apiGet } from "../api.js";
import { renderList } from "../main_series.js";
// Referencias
const btnBuscar = document.getElementById("btn-buscar");
const inputBusqueda = document.getElementById("busqueda-titulo");
const seriesContainer = document.getElementById("film");

async function buscarSeries() {
  const query = inputBusqueda.value.trim();

  if (query === "") {
    seriesContainer.innerHTML = "";
    window.location.reload();
    return;
  }
  
  try {
    const elements = await apiGet("/searchSeries/tv", { query });
    renderList(elements, seriesContainer);
  } catch (error) {
    console.error("Error en la búsqueda:", error);
  }
}

if (btnBuscar) {
  btnBuscar.addEventListener("click", buscarSeries);
}

if (inputBusqueda) {
  inputBusqueda.addEventListener("input", buscarSeries);
}