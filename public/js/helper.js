// Función para cambiar de página
export function updatePageUI(pageDisplay) {
  pageDisplay.textContent = `Página ${paginaActual}`;
}

// funcion mostrar mensaje
export function showMessage(text, isError = false) {
  const msg = document.getElementById("mensaje");

  msg.textContent = text;
  msg.style.color = isError ? "red" : "green";
}
