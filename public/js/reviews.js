const container = document.getElementById("reviews-container");

async function loadReviews() {

  const res = await fetch(
    `/Eurofilm/movie/getReviews?tmdb_id=${TMDB_ID}`
  );
  const data = await res.json();

  if (!data.ok) return;

  container.innerHTML = "";

  data.reviews.forEach(review => {

    const canEdit =
      CURRENT_USER_ID &&
      review.user_id == CURRENT_USER_ID;

    container.innerHTML += `
      <div class="review-card">

        <h3>${review.title ?? ''}</h3>

        <p>${review.comment ?? ''}</p>

        <small>
          ${review.username} · ${review.rating}/5
        </small>

        ${
          canEdit
          ? `
            <button data-id="${review.id}">
              Eliminar
            </button>
          `
          : ''
        }

      </div>
    `;
  });
}

loadReviews();