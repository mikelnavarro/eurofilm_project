const formRating = document.getElementById("form-rating");
const msg = document.getElementById("rating-msg");

formRating.addEventListener("submit", async (e) => {
  e.preventDefault();

  const rating = formRating.rating.value;
  const movieId = getMovieId();

  if (!rating) {
    msg.textContent = "Selecciona una puntuación";
    return;
  }

  const formData = new FormData();
  formData.append("movie_id", movieId);
  formData.append("rating", rating);
  formData.append("comment", comment);

  const res = await fetch("/Eurofilm/movies/rateMovie", {
    method: "POST",
    body: formData,
    credentials: "include"
  });

  const data = await res.json();

  if (res.status === 401) {
    msg.textContent = "Debes iniciar sesion";
    return;
  }

  msg.textContent = data.ok
    ? "Valoracion guardada"
    : data.error || "Error";
});