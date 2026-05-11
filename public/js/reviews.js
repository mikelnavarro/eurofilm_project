const container = document.getElementById("reviews-container");
// cargar reviews
async function loadReviews() {

  const res = await fetch(
    `/Eurofilm/movie/getReviews?tmdb_id=${TMDB_ID}`
  );
  const data = await res.json();

  if (!data.ok) return;

  container.innerHTML = "";

  data.reviews.forEach(review => {
const spoilerBadge = review.spoiler == 1
    ? `<span class="spoiler-badge">Spoiler</span>`
    : "";
    const canEdit =
      CURRENT_USER_ID &&
      review.user_id == CURRENT_USER_ID;

    container.innerHTML += `
      <div class="review-card">

        <h3>${review.title ?? ''}</h3>
        ${spoilerBadge}
        <p>${review.comment ?? ''}</p>

        <small>
          ${review.username} · ${review.rating}/5
        </small>
      </div>
    `;
  });
}

loadReviews();
async function loadAverage() {

  const res = await fetch(
    `/Eurofilm/movie/getMediaByMovie?tmdb_id=${TMDB_ID}`
  );

  const data = await res.json();

  if (!data.ok) return;

  document.getElementById("average-rating").textContent =
    data.average ?? "0";

  document.getElementById("total-reviews").textContent =
    `${data.total ?? 0} reseñas`;
}

loadAverage();