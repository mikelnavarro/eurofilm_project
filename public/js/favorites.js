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
            <a href="/Eurofilm/public/pantalla/card.php?id=${movie.tmdb_id}" alt="card"><div class="movie-card">
                <img src="https://image.tmdb.org/t/p/w300${movie.poster_path}">
                <h3>${movie.title}</h3>
                <p>${movie.release_date}</p>
                <button class="btn-remove-fav" data-id="MOVIE_ID">
                Quitar</button>
            </div>
        `;
  });
}

loadFavorites();
// remover
document.querySelectorAll(".remove-fav").forEach((btn) => {
  btn.addEventListener("click", async () => {
    const movieId = btn.dataset.id;

    const formData = new FormData();
    formData.append("movie_id", movieId);

    const res = await fetch("/Eurofilm/movie/removeFavorite", {
      method: "POST",
      body: formData,
      credentials: "include",
    });

    const data = await res.json();

    if (data.ok) {
      // eliminar card del DOM
      btn.closest(".movie-card").remove();
    } else {
      alert("Error al eliminar");
    }
  });
});
