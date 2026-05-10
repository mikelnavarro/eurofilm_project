const formRating = document.getElementById("form-rating");

const msg = document.getElementById("rating-msg");

formRating.addEventListener("submit", async (e) => {
  e.preventDefault();

  const rating = formRating.rating.value;
  //const movieId = getMovieId();

  if (!rating) {
    msg.textContent = "Selecciona una puntuación";
    return;
  }

  const formData = new FormData(formRating);

  const res = await fetch("/Eurofilm/movie/addReview", {
    method: "POST",
    body: formData,
    credentials: "include",
  });

  const data = await res.json();
  if (res.status === 401) {
    msg.textContent = "Debes iniciar sesion";
    return;
  }


  if (data.ok) {
    alert("Reseña guardada");
  }
  console.log(data);

  msg.textContent = data.ok ? "Valoracion guardada" : data.error || "Error";
});
