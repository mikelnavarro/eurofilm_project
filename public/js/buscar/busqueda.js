import { apiGet } from "../api.js";
import { BASE_URL } from "../configuracion.js";
import { renderList } from "../ui.js";

// Referencias
const btnBuscar = document.getElementById("btn-buscar");
const inputBusqueda = document.getElementById("busqueda-titulo");
const container = document.getElementById("peliculas");

// Input busqueda (evento Input)
inputBusqueda.addEventListener("input", async () => {
  container.innerHTML = "";
  const query = inputBusqueda.value.trim();
  console.log("QUERY:", query);

  // Si el input está vacío, limpiamos el contenedor y salimos
  if (query === "") {
    container.innerHTML = "";
    window.location.reload();
    return;
  }
  try {
    const movies = await apiGet("/api/search/movie/", { query: query });
    renderList(movies, container);
    console.log("IS ARRAY:", Array.isArray(movies));
    console.log("MOVIES:", movies);
  } catch (error) {
    console.error("Error en la búsqueda:", error);
  }
});
btnBuscar.addEventListener("click", async () => {
  const query = inputBusqueda.value.trim();
  console.log("QUERY:", query);
  if (query === "") {
    container.innerHTML = "";
    window.location.reload();
    return;
  }  try {
    const movies = await apiGet("/search/movie", { query: query });
    console.log("MOVIES:", movies);
    renderList(movies, container);
  } catch (error) {
    console.error("Error en la búsqueda:", error);
  }
});
