import { apiGet } from "./api.js";
import { render } from "./ui.js";
// Referencias
const btnBuscar = document.getElementById("btn-buscar");
const inputBusqueda = document.getElementById("busqueda-titulo");
const container = document.getElementById("peliculas");

btnBuscar.addEventListener("click", async () => {
  const query = inputBusqueda.value.trim();
  console.log("QUERY:", query);

  if (query === "") return;
  const movies = await apiGet("/search/movie", { query: query });
  console.log("MOVIES API:", movies);

  
  render(container, movies);
});