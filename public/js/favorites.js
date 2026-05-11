async function loadFavorites() {
  const res = await fetch("/Eurofilm/movie/getFavoritos", {
    credentials: "include",
  });

  const data = await res.json();
  // contenedor
  const container = document.getElementById("favorites-container");

  container.innerHTML = "";

  if (!data.movies.length) {
    container.innerHTML = "<p>No tienes favoritos aún</p>";
    return;
  }
  data.movies.forEach((movie) => {
    container.innerHTML += `
            <a href="/Eurofilm/public/movies/card.php?id=${movie.tmdb_id}" alt="card">
            <div class="movie-card">
                <img src="https://image.tmdb.org/t/p/w300${movie.poster_path}">
                <h3>${movie.title}</h3>
                </a>
                <p>${movie.release_date}</p>
                <button class="remove-fav" data-id="${movie.id}">
          Quitar de Favoritos
</button>
            </div>
        `;
  });
}

loadFavorites();
// remover
document.addEventListener("click", async (e) => {
  if (e.target.classList.contains("remove-fav")) {
    const movieId = e.target.dataset.id;

    const formData = new FormData();
    formData.append("movie_id", movieId);

    const res = await fetch("/Eurofilm/movie/removeFavorite", {
      method: "POST",
      body: formData,
      credentials: "include",
    });

    const data = await res.json();

    if (data.ok) {
      //eliminar Card
      btn.closest(".movie-card").remove();
      window.location.reload();
    } else {
      alert("Error al eliminar favorito");
    }
    location.reload();
  }
});
