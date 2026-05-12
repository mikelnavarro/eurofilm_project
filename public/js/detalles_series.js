import { BASE_URL } from "./configuracion.js";
import { renderWatchProviders } from "./funciones-carga.js";
// Referencias
const detailsView = document.getElementById("details");

// Obtener el ID de la API
function getSerieId() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}

// Pide detalles de Peli a la API DE TMDB
async function fetchSerie(id) {
  try {
    const response = await fetch(`${BASE_URL}/serie?id=${id}`);

    if (!response.ok) {
      throw new Error("Error en API");
    }

    return await response.json();
  } catch (error) {
    console.error("Error al cargar detalles:", error);
  }
}

// Obtiene detalles de una pelicula
function renderSerieDetails(detalles) {
  // Llenamos tus campos mediante IDs

  // Poster y Título
  const posterPath = detalles.poster_path
    ? `https://image.tmdb.org/t/p/w200${detalles.poster_path}`
    : "placeholder.jpg";
  document.getElementById("poster").src = posterPath;
  document.getElementById("poster").alt = detalles.name;
  document.getElementById("title").textContent =
    detalles.name || detalles.original_name;
  // Flag
  //renderFlag(detalles);
  // Lanzamiento
  if (detalles.first_air_date) {
    const fechaLanzamiento = new Date(detalles.first_air_date);
    document.getElementById("release-date").textContent =
      `Fecha de lanzamiento: ${fechaLanzamiento.toLocaleDateString("es-ES", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      })}`;
  }

  // Sinopsis - Overview
  document.getElementById("overview").textContent =
    `Sinopsis: ${detalles.overview}`;
  // Géneros
  const genresElement = document.getElementById("genres");
  if (detalles.genres && Array.isArray(detalles.genres)) {
    const nombresGeneros = detalles.genres.map((g) => g.name).join(", ");
    genresElement.textContent = `Géneros: ${nombresGeneros}`;
  } else {
    // Si no hay (o es una búsqueda general que no trae los nombres)
    genresElement.textContent = "Géneros: No disponibles";
  }
  // Countries
  renderCountries(detalles);
// Proveedores
renderWatchProviders(detalles);
  // Creadores
  if (detalles.created_by && detalles.created_by.length > 0) {
    document.getElementById("created-by").textContent =
      `Creado por: ${detalles.created_by.map((c) => c.name).join(", ")}`;
  }
  renderDirector(detalles);
  // companies
  renderCompanies(detalles);

  // trailer
  if (detalles.trailer) {
    const iframe = document.createElement("iframe");
    iframe.id = "trailer";
    iframe.src = `https://www.youtube.com/embed/${detalles.trailer}`;
    document.getElementById("info").appendChild(iframe);
  }
  // paises
  renderFlag(detalles);
  // Casting
  renderCast(detalles); // Link
  document.getElementById("link-provider").href = detalles.homepage;
}
showDetails();
async function showDetails() {
  const id = getSerieId();
  const detalles = await fetchSerie(id);
  if (detalles) {
    renderSerieDetails(detalles);
  }
}

/* Renderizado de carga de elenco de actores/actrices */
function renderCast(detalles) {
  const container_cast = document.getElementById("cast");
  container_cast.innerHTML = "";
  if (!detalles.cast || !Array.isArray(detalles.cast)) {
    return;
  }
  detalles.cast.forEach((actor) => {
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
/* Función Director */
function renderDirector(detalles) {
  const containerDirector = document.getElementById("director");
  const imgElement = document.getElementById("directing_path");
  const nameElement = document.getElementById("created-by");

  // verificamos si existe la propiedad y si tiene al menos un creador
  if (detalles.created_by && detalles.created_by.length > 0) {
    const director = detalles.created_by[0]; // al primero de la lista

    // Gestionamos la imagen
    const fotoUrl = director.profile_path
      ? `https://image.tmdb.org/t/p/w200${director.profile_path}`
      : "placeholder.png";

    imgElement.src = fotoUrl;
    imgElement.alt = director.name;

    // poner el nombre en texto
    if (nameElement) {
      nameElement.textContent = `Creado por: ${director.name}`;
    }
  } else {
    imgElement.style.display = "none";
  }
  containerDirector.append(nameElement, imgElement);
}

/* Cargar Banderitas */
function renderCountries(detalles) {
  const container_bandera = document.getElementById("countries");
  container_bandera.innerHTML = "";

  if (!detalles.production_countries) {
    return;
  }

  detalles.production_countries.forEach((country) => {
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
function renderFlag(detalles) {
  const flagContainer = document.getElementById("country-flag");
  if (!flagContainer) return;
  flagContainer.innerHTML = "";
  // codigo c
  let countryCode = "";

  if (detalles.origin_country && detalles.origin_country.length > 0) {
    // Caso para Series
    countryCode = detalles.origin_country[0];
  } else if (
    detalles.production_countries &&
    detalles.production_countries.length > 0
  ) {
    // Caso para Películas
    countryCode = detalles.production_countries[0].iso_3166_1;
  }
   if (countryCode) {
  const img = document.createElement("img");
  img.src = `https://flagcdn.com/w40/${countryCode.toLowerCase()}.png`;
  img.alt = detalles.origin_country;
  flagContainer.appendChild(img);
   }
}

/* Cargar imágenes de Productoras */
function renderCompanies(detalles) {
  const container_companies = document.getElementById("companies");
  container_companies.innerHTML = "";

  if (
    !detalles.production_companies ||
    !Array.isArray(detalles.production_companies)
  ) {
    return;
  }

  detalles.production_companies.forEach((company) => {
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
