// Referencias

const BASE_URL = "/Eurofilm/api/movies";
let paginaActual = 1;
  const movieContainer = document.getElementById("peliculas");

async function fetchMovies(pagina) {
  try {
    const res = await fetch(`${BASE_URL}?page=${pagina}`);

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
function renderList(movies) {

  if (!movieContainer) {
    console.error("La referencia del contenedor de 'peliculas' no encontrado.");
    return;
  }
  movieContainer.style.display = "grid";
  movies.forEach((movie) => {
    // Creamos los elementos uno a uno
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";
    // Si el usuario pulsa click, se abrirá la ventana de Card
    card.addEventListener("click", () => {
      window.location.href = `card.php?id=${movie.id}`;
    });
    // Cargar elemento uno a uno
    const img = document.createElement("img");
    img.src = movie.poster_path
      ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
      : "placeholder.png";
    img.alt = movie.title;
    const title = document.createElement("h3");
    title.textContent = movie.title;
    const id = document.createElement("span");
    id.textContent = movie.id;
    const releaseYear = document.createElement("p");
    releaseYear.textContent = movie.release_date?.slice(0, 4) ?? "—";

    card.append(img, title, releaseYear);
    // añadir la tarjeta al contenedor principal
    movieContainer.append(card);
  });
}
// eventos (Controladores de usuario)
// Al cargar el documento, mostramos algo por defecto
document.addEventListener("DOMContentLoaded", async () => {
  console.log("Iniciando carga de películas...");
  const movies = await fetchMovies(paginaActual);
  renderList(movies);
});

// Función para cambiar de página
