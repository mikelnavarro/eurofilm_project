// Referencias
const API_URL = "movies.php"; // Puente de PHP

// Función genérica para peticiones al puente PHP
async function request(params) {
  try {
    const queryParams = new URLSearchParams(params).toString();
    const response = await fetch(`${API_URL}?${queryParams}`);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error en la petición:", error);
    return null;
  }
}
// Busca películas
async function fetchMovies(query) {
  try {
    const response = await fetch(
      `${API_URL}?query=${encodeURIComponent(query)}`,
    );
    const data = await response.json();
    return data.results; // TMDB devuelve los resultados en .results
  } catch (error) {
    console.error("Error en búsqueda:", error);
    return [];
  }
}
// función de carga de detalles de Movies
function renderList(movies) {
  while (movieContainer.firstChild) {
    movieContainer.removeChild(movieContainer.firstChild);
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
    movieContainer.appendChild(card);
  });
}
// eventos (Controladores de usuario)
// Al cargar el documento, mostramos algo por defecto
document.addEventListener("DOMContentLoaded", async () => {
  // Podemos buscar "Batman", "Marvel" o lo que quieras que sea la portada
  const resultadosIniciales = await request({ action: "trending" });
  renderList(resultadosIniciales.results);
});

document.getElementById("btn-buscar").addEventListener("click", async () => {
  const busqueda = document.getElementById("busqueda-titulo").value;
  
  if (busqueda && busqueda.trim() !== "") {
    const datos = await request({
      action: "search",
      query: busqueda.trim(),
    });
    if (datos && datos.results) {
      renderList(datos.results);
    }
  }
});
