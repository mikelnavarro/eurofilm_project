import { PUBLIC_URL } from "../configuracion.js";
// Referencias

// Función asíncrona que encapsula la promesa de fetch
async function cargarComponente(ruta) {
  const respuesta = await fetch(ruta);

  // Si la respuesta no es correcta, lanzamos un error
  if (!respuesta.ok) {
    throw new Error(
      `Error ${respuesta.status}: No se pudo obtener el archivo.`,
    );
  }
  // Retorna el texto del HTML (implícitamente envuelto en una promesa)
  return await respuesta.text();
}

document.addEventListener("DOMContentLoaded", async () => {
  const contenedorHeader = document.getElementById('cabecera');
  if (contenedorHeader) {
    try {
      const contenidoHtml = await cargarComponente(`${PUBLIC_URL}/inc/header.html`);
      contenedorHeader.innerHTML = contenidoHtml;
    } catch (error) {
      console.error("Fallo al inyectar la cabecera:", error);
    }
  }
});
document.addEventListener("DOMContentLoaded", async () => {
  const container = document.getElementById('search-box');
  if (container) {
    try {
      const contenidoHtml = await cargarComponente(`${PUBLIC_URL}/movies/busqueda.html`);
      container.innerHTML = contenidoHtml;
    } catch (error) {
      console.error("Fallo al inyectar la barra para Buscar:", error);
    }
  }
});

// Footer
document.addEventListener("DOMContentLoaded", async () => {
  const container = document.getElementById('footer');
  if (container) {
    try {
      const contenidoHtml = await cargarComponente(`${PUBLIC_URL}/inc/footer.html`);
      container.innerHTML = contenidoHtml;
    } catch (error) {
      console.error("Fallo al inyectar la cabecera:", error);
    }
  }
});