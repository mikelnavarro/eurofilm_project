import { renderList } from "./ui.js";
// Referencias


// Variable de todas las pelis
let allMovies = [];

export function initFiltros(movies, movieContainer) {
  allMovies = movies;
  const selectPais = document.getElementById("select-pais");
  selectPais.addEventListener("change", () => {
    aplicarFiltros(container);
  });
}
// Módulo Genérico
export function applyFilters(items, filters) {
  return items.filter(item => {
    const matchCountry =
      !filters.country || item.country === filters.country;

    const matchGenre =
      !filters.genre || item.genre?.includes(filters.genre);

    return matchCountry && matchGenre;
  });
}
function aplicarFiltros() {
  const pais = document.getElementById("select-pais").value;

  let filtradas = allMovies;

  if (pais) {
    filtradas = allMovies.filter((movie) => {
      const porOrigen = movie.origin_country?.includes(pais);
      const porProduccion = movie.production_countries?.some((c) =>
        typeof c === "string" ? c === pais : c.iso_3166_1 === pais,
      );
      return porOrigen || porProduccion;
    });
  }
  renderList(filtradas, movieContainer);
}

function filtros() {
  const pais = selectPais.value; // Ej: "ES"
  let filtradas = allMovies;

  if (pais) {
    filtradas = allMovies.filter((movie) => {
      // 1. Intentar con origin_country (común en resultados de búsqueda/listados)
      const porOrigen = movie.origin_country?.includes(pais);

      // 2. Intentar con production_countries (por si acaso tu API lo incluye)
      const porProduccion = movie.production_countries?.some((c) =>
        typeof c === "string" ? c === pais : c.iso_3166_1 === pais,
      );

      return porOrigen || porProduccion;
    });
  }

  renderList(filtradas, movieContainer);
}
