// Función para cambiar de página
import { paginaActual } from "./main.js";
export function updatePageUI(pageDisplay) {
  pageDisplay.textContent = `Página ${paginaActual}`;
}