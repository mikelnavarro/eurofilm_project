import { TMDB_CONFIG, endpoints } from "./config.js";
import { getMovies, getPlataformas, getGeneros } from "./metodos.js";
import { renderHeader } from "./header.js";

// Cargar galería
renderHeader();
// RENDERIZADO
function renderGallery(movies) {
  const container = document.getElementById("contenedor-peliculas");
  container.innerHTML = "";

  if (movies.length === 0) {
    const noResults = document.createElement("p");
    noResults.textContent = "No se encontraron películas.";
    container.appendChild(noResults);
    return;
  }

  movies.forEach((movie) => {
    // IMPORTANTE: Creamos elementos NUEVOS en cada vuelta del bucle
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";
    card.addEventListener("click", () => {
      window.location.href = `card.html?id=${movie.id}`;
    });

    const img = document.createElement("img");
    img.src = movie.poster_path
      ? `${TMDB_CONFIG.IMAGE_URL}${movie.poster_path}`
      : "placeholder.png";
    img.alt = movie.title;
    const title = document.createElement("h3");
    title.textContent = movie.title;
    const id = document.createElement("span");
    id.textContent = movie.id;
    const rating = document.createElement("p");
    rating.textContent = `${movie.vote_average.toFixed(1)}`;
    const releaseYear = document.createElement("p");
    releaseYear.textContent = movie.release_date?.slice(0, 4) ?? "—";

    card.appendChild(img);
    card.appendChild(title);
    card.appendChild(id);
    card.appendChild(rating);
    card.appendChild(releaseYear);
    // añadir la tarjeta al contenedor principal
    container.appendChild(card);
  });
}

// INICIALIZADOR
async function initApp() {
  console.log("Intentando obtener películas..."); // <-- TEST 1
  const movies = await getMovies(endpoints.upcoming, { page: 1 });
  console.log("Películas recibidas:", movies);
  renderGallery(movies);
}

// Referencias
const btnBuscar = document.getElementById("btn-buscar");
const inputBusqueda = document.getElementById("busqueda-titulo");

const pais = document.getElementById("select-pais").value;
const plataforma = document.getElementById("select-plataforma").value;

btnBuscar.addEventListener("click", async () => {
  const query = inputBusqueda.value.trim();
  if (query === "") return;

  // Usamos el endpoint de búsqueda que ofrece TMDB
  const movies = await getMovies("/search/movie", { query: query });
  renderGallery(movies);
});

async function cargarFiltrosDinamicos(selectPlataforma) {
  const plataformas = await getPlataformas();

  plataformas.forEach((p) => {
    const option = document.createElement("option");
    option.value = p.provider_id;
    option.textContent = p.provider_name;
    selectPlataforma.appendChild(option);
  });
}

async function aplicarFiltros(pais) {
  
  // Parámetros
  const parametros = {
    page: 1,
    ...(pais && { with_origin_country: pais }),
    ...(plataforma && { with_watch_providers: plataforma }),
    watch_region: "ES", // Necesario para que el filtro de plataforma funcione
  };

  // Usamos el endpoint 'discover' que es el más potente para filtros
  const movies = await getMovies("/discover/movie", parametros);
  renderGallery(movies);
}
document
  .getElementById("select-pais")
  .addEventListener("change", aplicarFiltros);
document
  .getElementById("select-plataforma")
  .addEventListener("change", aplicarFiltros);

let paginaActual = 1;

async function cargarMasPeliculas() {
  paginaActual++;
  // Pedimos la siguiente página
  const nuevasPeliculas = await getMovies(endpoints.upcoming, {
    page: paginaActual,
  });
  // En lugar de limpiar con innerHTML = "", las añadimos al final
  renderGallery(nuevasPeliculas, true);
}

// Ejecutar al cargar el DOM
document.addEventListener("DOMContentLoaded", initApp);
