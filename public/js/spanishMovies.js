import { apiGet } from "./api.js";
import { render } from "./ui.js";

const container = document.getElementById("peliculas");
let pagina = 1;

async function loadSpanishMovies() {
  const movies = await apiGet("/movie/spanishMovies", { page: pagina });
  render(container, movies);
}

document.addEventListener("DOMContentLoaded", () => {
  loadSpanishMovies();
});