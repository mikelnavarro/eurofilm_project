import { apiGet } from "./api.js";
import { render } from "./ui.js";
// Referencias
const btnBuscar = document.getElementById("btn-buscar");
const inputBusqueda = document.getElementById("busqueda-titulo");
const seriesContainer = document.getElementById("film");



btnBuscar.addEventListener("input", async () => {
  const query = inputBusqueda.value.trim();
  console.log("QUERY:", query);

  if (query === "") return;
  const elements = await apiGet("/searchSeries/tv", { query: query });
  console.log("TV API:", elements);


  
  render(seriesContainer, movies);
});


// Búsqueda
inputBusqueda.addEventListener("input", async () => {
  const query = inputBusqueda.value.trim();
  console.log("QUERY:", query);

  // Si el input está vacío, limpiamos el contenedor y salimos
  if (query === "") {
    seriesContainer.innerHTML = ""; 
    window.location.reload();
    return;
  }
  
  try {
    const elements = await apiGet("/searchSeries", { query: query });
    console.log("TV API:", elements);

    render(seriesContainer, elements);
  } catch (error) {
    console.error("Error en la búsqueda:", error);
  }
});