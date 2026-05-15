import { BASE_URL } from "./configuracion.js";
import { initFiltros } from "./filtros.js";
import { updatePageUI } from "./helper.js";
import { renderList } from "./ui.js";

// Referencias
export let paginaActual = 1;
const movieContainer = document.getElementById("peliculas");

// Para las paginas botones
const prevBtn = document.getElementById("prev-page");
const nextBtn = document.getElementById("next-page");
const pageDisplay = document.getElementById("current-page-display");

async function fetchMovies(pagina) {
  try {
    const res = await fetch(`${BASE_URL}/movies?page=${pagina}`);

    if (!res.ok) {
      throw new Error(`Error HTTP: ${res.status}`);
    }

    const data = await res.json();
    console.log("Datos recibidos:", data);
    return data.results;
  } catch (error) {
    console.error("Error en fetchMovies:", error);
    return null;
  }
}

// eventos (Controladores de usuario)
prevBtn.addEventListener("click", async () => {
  if (paginaActual > 1) {
    paginaActual--;
    await loadMovies();
  }
});
nextBtn.addEventListener("click", async () => {
  paginaActual++;
  await loadMovies();
});
// Al cargar el documento, mostramos algo por defecto
async function loadMovies() {
  console.log("Iniciando carga de películas...");
  const movies = await fetchMovies(paginaActual);
  renderList(movies,movieContainer);
  updatePageUI(pageDisplay);
  initFiltros(movies,movieContainer);
}
loadMovies();
