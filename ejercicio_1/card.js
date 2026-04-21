// Referencias
// Connection API
const API_TOKEN =
  "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI2Y2I3NTkwNjQxNDI5MzkwNDc1YzQ3OTI2NzU0NmUzOCIsIm5iZiI6MTc2Nzk3MzkzNy45OCwic3ViIjoiNjk2MTI0MzE3ZWQyY2MwNWY3MzY0MTAxIiwic2NvcGVzIjpbImFwaV9yZWFkIl0sInZlcnNpb24iOjF9.x5dlUTPogbnLsvla7OrBbfVGOnE9AJE0Zj_4hzlfxNw";
const BASE_URL = "https://api.themoviedb.org/3";
const options = {
  method: "GET",
  headers: {
    accept: "application/json",
    Authorization: `Bearer ${API_TOKEN}`,
  },
};
// endpoints
const endpoints = {
  popular: "/movie/popular",
  topRated: "/movie/top_rated",
  upcoming: "/movie/upcoming",
  trending: "/trending/movie/week",
  search: "/search/movie",
};

async function fetchTMDB(endpoint) {
  const res = await fetch(`${BASE_URL}${endpoint}`, options);
  return res.json();
}

async function obtenerPelicula(id) {
  try {
    const pelicula = await fetchTMDB(`/movie/${id}`);
    mostrarPelicula(pelicula);
  } catch (error) {
    console.error("Error:", error);
  }
}

function mostrarPelicula(pelicula) {
  const contenedor = document.getElementById("peli");
  // Creamos el elemento div para la tarjeta
  const card = document.createElement("div");
  card.classList.add("pelicula-card");


  const imagen = `https://image.tmdb.org/t/p/w500${pelicula.poster_path}`;

  card.innerHTML = `
            <h2>${pelicula.title}</h2>
            <h2>${pelicula.title}</h2>
            <p>${pelicula.overview}</p>
            <img src="${imagen}" alt="${pelicula.title}">
        `;

  contenedor.appendChild(card);

}
obtenerPelicula(550);
