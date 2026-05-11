import { render } from "./ui.js";
// Referencias
const selectPais = document.getElementById("select-pais");
const container = document.getElementById("peliculas");

// Variable de todas las pelis
let allMovies = [];

export function initFiltros(movies) {
  allMovies = movies;

  selectPais.addEventListener("change", aplicarFiltros);
}
function aplicarFiltros() {
  const pais = selectPais.value;

  let filtradas = allMovies;

  if (pais) {
    filtradas = allMovies.filter(movie =>
      movie-production_countries?.includes(pais) ||
      movie.production_countries?.some(c => c.iso_3166_1 === pais)
    );
  }

  render(container, filtradas);
}
