import { BASE_URL } from "./configuracion.js";
import { getMovieIdFromUrl } from "./helpers/helper.js";
import {
  renderDirectorMovie,
  renderWatchProviders,
  renderWritersMovie,
} from "./funciones-carga.js";


// Referencias
const detailsView = document.getElementById("movie-details");

// Pide detalles de Peli a la API DE TMDB
async function fetchMovie(id) {
  try {
    const res = await fetch(`${BASE_URL}/api/movie?id=${id}`);

    if (!res.ok) {
      throw new Error("Error en API");
    }
    const data = await res.json();
    console.log("Datos recibidos:", data);
    return data;
  } catch (error) {
    console.error("Error al cargar detalles:", error);
  }
}
// Obtiene detalles de una pelicula
function renderMovieDetails(movie) {
  // Llenamos tus campos mediante IDs
  document.getElementById("movie-poster").src = movie.poster_path
    ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
    : "placeholder.png";
  document.getElementById("movie-poster").alt = movie.title;
  document.getElementById("movie-title").textContent = movie.title;
  // Flag
  //renderFlag(movie);
  // Lanzamiento
  document.getElementById("movie-release-date").textContent =
    `Fecha de lanzamiento: ${movie.release_date}`;
  const fecha = new Date(movie.release_date);

  document.getElementById("movie-release-date").textContent =
    `Fecha de lanzamiento: ${fecha.toLocaleDateString("es-ES", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    })}`;

  // Sinopsis - Overview
  document.getElementById("movie-overview").textContent =
    `Sinopsis: ${movie.overview}`;
  const genresElement = document.getElementById("movie-genres");
  if (movie.genres && Array.isArray(movie.genres)) {
    const nombresGeneros = movie.genres.map((g) => g.name).join(", ");
    genresElement.textContent = `Géneros: ${nombresGeneros}`;
  } else {
    // Si no hay (o es una búsqueda general que no trae los nombres)
    genresElement.textContent = "Géneros: No disponibles";
  }
  // proveedor
  renderWatchProviders(movie);
  // Countries
  renderCountries(movie);
  // flag
  renderFlag(movie);
  // Productoras - Companies
  renderCompanies(movie);
  // Directing
  renderDirectorMovie(movie);
  // Guion
  renderWritersMovie(movie);
  // Trailer
  if (movie.trailer) {
    const iframe = document.createElement("iframe");
    iframe.id = "movie-trailer";
    iframe.src = `https://www.youtube.com/embed/${movie.trailer}`;
    document.getElementById("movie-info").appendChild(iframe);
  }
  // Casting
  renderCast(movie);
  // Link
  document.getElementById("homepage").href = movie.homepage;
}
showDetails();
async function showDetails() {
  const id = getMovieIdFromUrl();

  if (!id) {
    console.error("No se encontró el ID en la URL");
    return;
  }

  const detalles = await fetchMovie(id);
  if (detalles) {
    renderMovieDetails(detalles);
  }
}

/* Renderizado de carga de elenco de actores/actrices */
function renderCast(movie) {
  const container_cast = document.getElementById("cast");
  container_cast.innerHTML = "";
  if (!movie.cast || !Array.isArray(movie.cast)) {
    return;
  }
  movie.cast.forEach((actor) => {
    const li = document.createElement("li");

    const img = document.createElement("img");

    if (actor.profile_path) {
      img.src = `https://image.tmdb.org/t/p/w200${actor.profile_path}`;
      img.alt = actor.name;
    }

    const name = document.createElement("span");
    name.textContent = actor.name;

    const character = document.createElement("span");
    character.className = "Character";
    character.textContent = actor.character;

    li.append(img, name, character);
    container_cast.appendChild(li);
  });
}
/* Cargar Banderitas */
function renderCountries(movie) {
  const container_bandera = document.getElementById("countries");
  container_bandera.innerHTML = "";

  if (!movie.production_countries) {
    return;
  }

  movie.production_countries.forEach((country) => {
    const name = document.createElement("p");
    name.textContent = country.name;
    const img = document.createElement("img");

    img.src = `https://flagcdn.com/w40/${country.iso_3166_1.toLowerCase()}.png`;
    img.alt = country.name;
    img.title = country.name;

    name.append(img);
    container_bandera.appendChild(name, img);
  });
}
function renderFlag(movie) {
  const flagContainer = document.getElementById("country-flag");
  if (!flagContainer) return;
  flagContainer.innerHTML = "";
  // codigo c
  let countryCode = "";

  if (movie.origin_country && movie.origin_country.length > 0) {
    // Caso para Series
    countryCode = movie.origin_country[0];
  } else if (
    movie.production_countries &&
    movie.production_countries.length > 0
  ) {
    // Caso para Películas
    countryCode = movie.production_countries[0].iso_3166_1;
  }
  if (countryCode) {
    const img = document.createElement("img");
    img.src = `https://flagcdn.com/w40/${countryCode.toLowerCase()}.png`;
    img.alt = movie.origin_country;
    flagContainer.appendChild(img);
  }
}
/* Cargar imágenes de Productoras */
function renderCompanies(movie) {
  const container_companies = document.getElementById("companies");
  container_companies.innerHTML = "";

  if (
    !movie.production_companies ||
    !Array.isArray(movie.production_companies)
  ) {
    return;
  }

  movie.production_companies.forEach((company) => {
    const wrapper = document.createElement("div");
    wrapper.className = "company";
    const img = document.createElement("img");

    if (company.logo_path) {
      img.src = `https://image.tmdb.org/t/p/w200${company.logo_path}`;
      img.alt = company.name;
    } else {
      img.style.display = "none";
    }

    const name = document.createElement("span");
    name.textContent = company.name;
    wrapper.append(img, name);
    container_companies.appendChild(wrapper);
  });
}
// buscamos añadir a favoritos

const movieData = document.getElementById("movie-data");
const tmdbId = getMovieIdFromUrl();
const favBtn = document.getElementById("btn-favorito");

favBtn.addEventListener("click", async () => {
  const formData = new FormData();
  formData.append("tmdb_id", tmdbId);


  // carga la response
  const res = await fetch(`${BASE_URL}/movie/addFavorite`, {
    method: "POST",
    body: formData,
    credentials: "include",
  });
  const data = await res.json();
  if (data.ok) {
    favBtn.textContent = "En favoritos";
    showMessage("Ya esta en favoritos");
    alert("Ya esta en favoritos");
    window.location.reload();
  } else {
    showMessage("No esta en favoritos. Hubo algún problema.", true);
    alert("No esta en favoritos. Hubo algún problema.");
  }
});
// funcion mostrar mensaje
export function showMessage(text, isError = false) {
  const mensaje = document.getElementById("mensaje");

  mensaje.textContent = text;
  mensaje.style.color = isError ? "red" : "green";
}
