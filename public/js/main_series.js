import { BASE_URL } from "./configuracion.js";

import { initFiltros } from "./filtros.js";
import { updatePageUI } from "./helper.js";
// Referencias
let paginaActual = 1;
const container = document.getElementById("film");


// Para las paginas botones
const prevBtn = document.getElementById("prev-page");
const nextBtn = document.getElementById("next-page");
const pageDisplay = document.getElementById("current-page-display");

async function fetchMovies(pagina) {
  try {
    const res = await fetch(`/Eurofilm/api/series?page=${pagina}`);

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


// función de carga de detalles de Movies
export function renderList(series,seriesContainer) {
  if (!container) {
    console.error("La referencia del contenedor de 'peliculas' no encontrado.");
    return;
  }
  series.forEach((serie) => {
    // Creamos los elementos uno a uno
    const card = document.createElement("div");
    card.className = "serie";
    card.style.cursor = "pointer";
    // Si el usuario pulsa click, se abrirá la ventana de Card
    card.addEventListener("click", () => {
      window.location.href = `card.php?id=${serie.id}`;
    });
    // Cargar elemento uno a uno
    const img = document.createElement("img");
    img.src = serie.poster_path
      ? `https://image.tmdb.org/t/p/w200${serie.poster_path}`
      : "placeholder.png";
    img.alt = serie.alt;
    const title = document.createElement("h3");
    title.textContent = serie.name;
    // ID
    const id = document.createElement("span");
    id.textContent = serie.id;


    const releaseYear = document.createElement("p");
    releaseYear.textContent = serie.first_air_date?.slice(0, 4) ?? "—";

    card.append(img, title, releaseYear);
    // añadir la tarjeta al contenedor principal
    container.append(card);
  });
}
// eventos (Controladores de usuario)
prevBtn.addEventListener("click", async () => {
  if (paginaActual > 1) {
    paginaActual--;
    await loadElements();
  }
});
nextBtn.addEventListener("click", async () => {
  paginaActual++;
  await loadElements();
});
// Al cargar el documento, mostramos algo por defecto
async function loadElements() {
  console.log("Iniciando carga de películas...");
  const series = await fetchMovies(paginaActual);
  renderList(series);
  updatePageUI(pageDisplay);
  initFiltros(series);
}
loadElements();
