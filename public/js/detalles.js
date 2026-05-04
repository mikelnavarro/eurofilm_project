import { BASE_URL } from "./configuracion.js";
// Referencias
const detailsView = document.getElementById("movie-details");

// Obtener el ID de la API
function getMovieId() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}
// Pide detalles de Peli a la API DE TMDB
async function fetchMovie(id) {
  try {
    const response = await fetch(`${BASE_URL}/movie?id=${id}`);

    if (!response.ok) {
      throw new Error("Error en API");
    }

    return await response.json();
  } catch (error) {
    console.error("Error al cargar detalles:", error);
  }
}
// Obtiene detalles de una pelicula
function renderMovieDetails(movie) {
  // Llenamos tus campos mediante IDs
  document.getElementById("movie-poster").src = movie.poster_path
    ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
    : "placeholder.png";
  document.getElementById("movie-poster").alt = movie.title;
  document.getElementById("movie-title").textContent = movie.title;
  document.getElementById("movie-release-date").textContent =
    `Fecha de lanzamiento: ${movie.release_date}`;
  document.getElementById("movie-overview").textContent =
    `Sinopsis: ${movie.overview}`;
  const genresElement = document.getElementById("movie-genres");
  if (movie.genres && Array.isArray(movie.genres)) {
    const nombresGeneros = movie.genres.map((g) => g.name).join(", ");
    genresElement.textContent = `Géneros: ${nombresGeneros}`;
  } else {
    // Si no hay (o es una búsqueda general que no trae los nombres)
    genresElement.textContent = "Géneros: No disponibles";
  }

  document.getElementById("countries").textContent = movie.production_countries
    .map((c) => c.name)
    .join(", ");

  document.getElementById("companies").textContent = movie.production_companies
    .map((p) => p.name)
    .join(", ");

  // Trailer
  if (movie.trailer) {
    const iframe = document.createElement("iframe");
    iframe.src = `https://www.youtube.com/embed/${movie.trailer}`;
    document.getElementById("movie-info").appendChild(iframe);
  }
  // Casting
  const castList = document.getElementById("cast");
  movie.cast.slice(0, 5).forEach((actor) => {
    const li = document.createElement("li");
    li.textContent = actor.name;
    castList.appendChild(li);
  });
}
showDetails();
async function showDetails() {
  const id = getMovieId();
  const detalles = await fetchMovie(id);
  if (detalles) {
    renderMovieDetails(detalles);
  }
}
