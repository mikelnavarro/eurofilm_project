import { BASE_URL } from "./configuracion.js";
async function cargarPerfil() {
  const res = await fetch("/Eurofilm/auth/perfil", {
    credentials: "include",
  });

  const data = await res.json();
  if (!data.ok) {
    data.error;
    return;
  }

  if (data.ok) {
    renderizar(data.usuario);
    console.log("USER:", data);
  }
}

// Ver usuario

function renderizar(user) {
  document.getElementById("username").value = user.username;
  document.getElementById("email").value = user.email;
  document.getElementById("country").value = user.country;
  document.getElementById("bio").value = user.bio;
}
cargarPerfil();
// Ver reseñas
// contenedor
const container = document.getElementById("user-reviews-container");

async function loadUserReviews() {
  const res = await fetch("/Eurofilm/movie/getUserReviews", {
    credentials: "include",
  });
  const data = await res.json();

  if (!data.ok) return;

  container.innerHTML = "";

  data.reviews.forEach((r) => {
    container.innerHTML += `
    <div class="review-card">
        <div class="movie-mini">
          <img src="https://image.tmdb.org/t/p/w200${r.poster_path}" />
          <h4>${r.title}</h4></div>
        <p><b>${r.rating}/5</b></p>
        <p>${r.comment ?? "Sin comentarios..."}</p>
        <small>${r.visibility}</small>
          <button class="btn btn-delete-review" data-id="${r.id}">Borrar</button></div>

    `;
  });
}

loadUserReviews();
document.addEventListener("click", async (e) => {
  if (e.target.classList.contains("btn-delete-review")) {
    const reviewId = e.target.dataset.id;

    if (!confirm("¿Eliminar reseña?")) return;

    const formData = new FormData();
    formData.append("review_id", reviewId);

    const res = await fetch("/Eurofilm/movie/deleteReview", {
      method: "POST",
      body: formData,
      credentials: "include",
    });

    const data = await res.json();

    if (data.ok) {
      e.target.closest(".review-card").remove();
    } else {
      alert("Error al eliminar");
    }
  }
});
