const container = document.getElementById("peliculas");
export function render(movies) {
  container.innerHTML = "";

  if (!Array.isArray(movies)) return;

  movies.forEach((movie) => {
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";
    // Si el usuario pulsa click, se abrirá la ventana de Card
    card.addEventListener("click", () => {
      window.location.href = `card.php?id=${movie.id}`;
    });
    const title = document.createElement("h3");
    title.textContent = movie.title;

    const img = document.createElement("img");
    img.src = movie.poster_path
      ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
      : "placeholder.png";

    const releaseYear = document.createElement("p");
    releaseYear.textContent = movie.release_date?.slice(0, 4) ?? "—";

    card.append(img, title, releaseYear);
    container.appendChild(card);
  });
}
