import { BASE_URL } from "./configuracion.js";
import { apiGet } from "./api.js";
import { render } from "./ui.js";
import { updatePageUI } from "./helper.js";


// Referencias
let paginaActual = 1;
const movieContainer = document.getElementById("peliculas");
// Para las paginas botones
const prevBtn = document.getElementById("prev-page");
const nextBtn = document.getElementById("next-page");
const pageDisplay = document.getElementById("current-page-display");

// Fetch petición a la Api
async function fetchSpanishMovies(pagina) {
  try {
    const res = await fetch(`${BASE_URL}/spanishMovies?page=${pagina}`);

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



// Rendirizar carga
function renderList(movies) {
  if (!movieContainer) return;

  movieContainer.innerHTML = "";

  movies.forEach((movie) => {
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";

    card.addEventListener("click", () => {
      window.location.href = `card.php?id=${movie.id}`;
    });

    const img = document.createElement("img");
    img.src = movie.poster_path
      ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
      : "placeholder.png";

    const title = document.createElement("h3");
    title.textContent = movie.title;

    const year = document.createElement("p");
    year.textContent = movie.release_date?.slice(0, 4) ?? "—";

    card.append(img, title, year);
    movieContainer.appendChild(card);
  });
}
loadSpanishMovies();


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
  const movies = await fetchSpanishMovies(paginaActual);
  renderList(movies);
  updatePageUI(pageDisplay);
}

