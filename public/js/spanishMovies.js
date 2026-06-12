import { BASE_URL } from "./configuracion.js";
import { renderList } from "./ui.js";
import { updatePageUI } from "./helpers/helper.js";
import { initFiltros } from "./filtros.js";


// Referencias
export let paginaActual = 1;
const movieContainer = document.getElementById("peliculas");
// Para las paginas botones
const prevBtn = document.getElementById("prev-page");
const nextBtn = document.getElementById("next-page");
const pageDisplay = document.getElementById("current-page-display");

// Fetch petición a la Api
async function fetchSpanishMovies(pagina) {
  try {
    const res = await fetch(`${BASE_URL}/api/spanishMovies?page=${pagina}`);

    if (!res.ok) {
      throw new Error(`Error HTTP: ${res.status}`);
    }

    const data = await res.json();
    console.log("Datos recibidos:", data);
    return data.results;
  } catch (error) {
    console.error("Error fetchSpanishMovies:", error);
    return null;
  }
}
// eventos
prevBtn.addEventListener("click", async () => {
  if (paginaActual > 1) {
    paginaActual--;
    await loadSpanishMovies();
  }
});
nextBtn.addEventListener("click", async () => {
  paginaActual++;
  await loadSpanishMovies();
});
async function loadSpanishMovies() {
  console.log("Iniciando carga de películas...");
  const movies = await fetchSpanishMovies(paginaActual);
  renderList(movies,movieContainer);
  updatePageUI(paginaActual,pageDisplay);
  //initFiltros(movies);
}
loadSpanishMovies();