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

  if (!data.ok) {
    msg.textContent = data.error || "Error";
    return;
  }
  if (data.ok) {
    if (data.updated) {
      msg.textContent = "Reseña actualizada";
      window.location.reload();
    } else {
      msg.textContent = "Reseña creada";
      window.location.reload();
    }
    
  }

  if (data.hasReviewed) {
    document.getElementById("form-rating").style.display = "none";
  }
  if (data.review) {
    form.rating.value = data.review.rating;
    form["review-title"].value = data.review.title;
    form.comment.value = data.review.comment;
  }

  console.log(data);
  msg.style.color = "green";
});
