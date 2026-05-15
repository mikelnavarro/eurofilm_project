import { renderList } from "./ui.js";
// Referencias
const movieContainer = document.getElementById("peliculas");
const selectGenero = document.getElementById("select-genero");
const selectPais = document.getElementById("select-pais");
const selectSort = document.getElementById("select-sort");
// Variable de todas las pelis
let allMovies = [];

export function initFiltros(movies) {
  allMovies = movies;
  selectSort.addEventListener("change", aplicarFiltros);
  selectGenero.addEventListener("change", aplicarFiltros);
  selectPais.addEventListener("change", aplicarFiltros);
}
// Módulo Genérico
function applyFilters(items, filters) {
  return items.filter((item) => {
    const matchCountry = !filters.country || item.country === filters.country;

    const matchGenre = !filters.genre || item.genre?.includes(filters.genre);

    return matchCountry && matchGenre;
  });
}
function aplicarFiltros() {
  const pais = selectPais.value;
  const sort = selectSort.value;
  const genero = selectGenero.value;

  // pelis
  let filtradas = [...allMovies];
  if (pais) {
    filtradas = allMovies.filter((movie) => {
      const porOrigen = movie.origin_country?.includes(pais);
      const porProduccion = movie.production_countries?.some((c) =>
        typeof c === "string" ? c === pais : c.iso_3166_1 === pais,
      );
      return porOrigen || porProduccion;
    });
  }
  if (genero) {
    filtradas = filtradas.filter((movie) =>
      movie.genre_ids?.includes(Number(genero)),
    );
  }
  // Ordenación
  if (sort) {
    filtradas = [...filtradas].sort((a, b) => {
      switch (sort) {
        case "year_desc":
          return (b.release_date ?? "").localeCompare(a.release_date ?? "");

        case "year_asc":
          return (a.release_date ?? "").localeCompare(b.release_date ?? "");

        case "rating_desc":
          return (b.vote_average ?? 0) - (a.vote_average ?? 0);

        case "rating_asc":
          return (a.vote_average ?? 0) - (b.vote_average ?? 0);

        default:
          return 0;
      }
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
