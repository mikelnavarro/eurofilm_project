import { renderHeader } from "./header.js";
import { TMDB_CONFIG } from "./config.js";
import { getMovieDetails } from "./metodos.js";


renderHeader();
// Función para renderizar los detalles
function renderMovieDetails(movie) {
  document.getElementById("movie-poster").src = movie.poster_path
    ? `${TMDB_CONFIG.IMAGE_URL}${movie.poster_path}`
    : "placeholder.png";
  document.getElementById("movie-poster").alt = movie.title;
  document.getElementById("movie-title").textContent = movie.title;
  document.getElementById("movie-release-date").textContent =
    `Fecha de lanzamiento: ${movie.release_date}`;
  document.getElementById("movie-rating").textContent =
    `Puntuación: ${movie.vote_average.toFixed(1)}`;
  document.getElementById("movie-overview").textContent = movie.overview;
  document.getElementById("movie-genres").textContent =
    `Géneros: ${movie.genres.map((g) => g.name).join(", ")}`;
}

// Inicializar la aplicación
async function initCard() {
  const urlParams = new URLSearchParams(window.location.search);
  const movieId = urlParams.get("id");

  if (!movieId) {
    document.getElementById("movie-details").innerHTML =
      "<p>ID no proporcionado.</p>";
    return;
  }

  const movie = await getMovieDetails(movieId);

  if (!movie) {
    document.getElementById("movie-details").innerHTML =
      "<p>Error al cargar la película.</p>";
    return;
  }
  renderMovieDetails(movie);


  const btn = document.getElementById("btn-favorito");
  btn.addEventListener("click", () => {
    guardarFavoritoLocal(movie.id, movie.title);
  });
}

// Ejecutar al cargar el DOM
document.addEventListener("DOMContentLoaded", initCard);



// Función para guardar en LocalStorage
function guardarFavoritoLocal(id, titulo) {
    // Simulamos un usuario (luego vendrá de la sesión de PHP)
    const usuarioActual = "Usuario_Prueba";

    // Obtenemos lo que ya haya guardado o un array vacío
    let favoritos = JSON.parse(localStorage.getItem("mis_favoritos")) || [];

    // Comprobamos si ya existe
    if (favoritos.some(fav => fav.id === id)) {
        alert("Esta película ya está en tus favoritos");
        return;
    }

    // Guardamos objeto con id, título y usuario
    favoritos.push({ id, titulo, usuario: usuarioActual, fecha: new Date().toLocaleDateString() });
    localStorage.setItem("mis_favoritos", JSON.stringify(favoritos));
}
// Función para conectar con la base de datos (datos en JSON, con BD PHP)
async function marcarFavorito(id) {
  const response = await fetch("api/guardar_favorito.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_tmdb: id }),
  });
  const resultado = await response.json();
  alert(resultado.mensaje);
}
