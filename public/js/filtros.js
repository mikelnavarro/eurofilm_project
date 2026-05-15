import { fetchMovies } from './main.js';
import { render } from "./ui.js";
// Referencias
const selectPais = document.getElementById("select-pais");
// Variable de todas las pelis
let allMovies = [];

export function initFiltros(movies) {
  allMovies = movies;

  selectPais.addEventListener("change", filtros);
}
function aplicarFiltros() {
  const pais = selectPais.value;

  let filtradas = allMovies;

  if (pais) {
    filtradas = allMovies.filter(movie =>
      movie.origin_country?.includes(pais) ||
      movie.origin_country?.some(c => c.iso_3166_1 === pais)
    );
  }

  render(filtradas);
}

function filtros() {
  const pais = selectPais.value; // Ej: "ES"
  let filtradas = allMovies;

  if (pais) {
    filtradas = allMovies.filter(movie => {
      // 1. Intentar con origin_country (común en resultados de búsqueda/listados)
      const porOrigen = movie.origin_country?.includes(pais);
      
      // 2. Intentar con production_countries (por si acaso tu API lo incluye)
      const porProduccion = movie.production_countries?.some(c => 
        (typeof c === 'string' ? c === pais : c.iso_3166_1 === pais)
      );

      return porOrigen || porProduccion;
    });
  }

  render(filtradas);
}
