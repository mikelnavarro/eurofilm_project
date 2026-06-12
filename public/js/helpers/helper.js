// Función para cambiar de página
export function updatePageUI(paginaActual, pageDisplay) {
  pageDisplay.textContent = `Página ${paginaActual}`;
}

// Obtener el ID de película/serie desde la URL
export function getMovieIdFromUrl() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}