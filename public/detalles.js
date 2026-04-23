// Referencias
const API_URL = "movies.php"; // Puente de PHP
const detailsView = document.getElementById("movie-details");

// detalles
document.addEventListener("DOMContentLoaded", async () => {
  // 1. Extraer el ID de la URL (ej: card.html?id=123)
  const params = new URLSearchParams(window.location.search);
  const movieId = params.get("id");
  if (!movieId) {
    console.error("No se encontró el ID de la película");
    // Opcional: redirigir al index si no hay ID
    window.location.href = "index.php";
    return;
  }
  showDetails(movieId);
});

async function fetchDetails(id) {
  try {
    const response = await fetch(`${API_URL}?id=${id}`);
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
  document.getElementById("movie-overview").textContent = movie.overview;
  // Localiza donde pones los géneros y cámbialo por esto:
  const genresElement = document.getElementById("movie-genres");
  if (movie.genres && Array.isArray(movie.genres)) {
    const nombresGeneros = movie.genres.map((g) => g.name).join(", ");
    genresElement.textContent = `Géneros: ${nombresGeneros}`;
  } else {
    // Si no hay (o es una búsqueda general que no trae los nombres)
    genresElement.textContent = "Géneros: No disponibles";
  }
}

async function showDetails(id) {
  const detalles = await fetchDetails(id);
  if (detalles) {
    renderMovieDetails(detalles);
  }
}
