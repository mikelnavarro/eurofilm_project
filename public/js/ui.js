
// función de carga de detalles de Movies
export function renderList(movies,movieContainer) {
  if (!movieContainer) {
    console.error("La referencia del contenedor de 'peliculas' no encontrado.");
    return;
  }
  if (!Array.isArray(movies)) return;
  movieContainer.innerHTML = "";
  movies.forEach((movie) => {
    // Creamos los elementos uno a uno
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";
    // Si el usuario pulsa click, se abrirá la ventana de Card
    card.addEventListener("click", () => {
      window.location.href = `card.html?id=${movie.id}`;
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
