/* Función Director */
export function renderDirector(movie) {
  const containerDirector = document.getElementById("director");
  const imgElement = document.getElementById("directing_path");
  const nameElement = document.getElementById("created-by");

  // verificamos si existe la propiedad y si tiene al menos un creador
  if (movie.credits && movie.credits.crew > 0) {
    const director = detalles.credits.crew.find(
      (persona) => persona.job === "Director",
    );
    if (director) {
      // Gestionamos la imagen
      const fotoUrl = director.profile_path
        ? `https://image.tmdb.org/t/p/w200${director.profile_path}`
        : "placeholder.png";

      imgElement.src = fotoUrl;
      imgElement.alt = director.name;

      // poner el nombre en texto
      if (nameElement) {
        nameElement.textContent = `Creado por: ${director.name}`;
      }
    } else {
      imgElement.style.display = "none";
    }
  }

  containerDirector.append(nameElement, imgElement);
}

export function renderWatchProviders(detalles) {
  const container = document.getElementById("provider-container");
  container.innerHTML = "";
  if (!detalles.watch_providers || !detalles.watch_providers.results) {
    container.textContent = "No hay información de proveedores disponible.";
    return;
  }
  // región
  const providers = detalles.watch_providers.results.ES;

  if (!providers) {
    container.textContent = "No disponible en tu región.";
    return;
  }
  // icono
  const crearIcono = (provider) => {
    const img = document.createElement("img");
    img.src = `https://image.tmdb.org/t/p/w200${provider.logo_path}`;
    img.alt = provider.provider_name;
    img.title = provider.provider_name; // Muestra el nombre al pasar el ratón
    img.style.width = "40px";
    img.style.borderRadius = "8px";
    img.style.marginRight = "10px";
    return img;
  };
  const nombresCat = {
    flatrate: "Suscripción",
    rent: "Alquiler",
    buy: "Compra",
  };
  const categorias = ["flatrate", "rent", "buy"];


  categorias.forEach((cat) => {
    if (providers[cat] && providers[cat].length > 0) {
      const titulo = document.createElement("h4");
      titulo.textContent = nombresCat[cat];
      titulo.style.width = "100%"; // Para que salte de línea
      titulo.style.margin = "10px 0 5px 0";
      container.appendChild(titulo);
      if (providers[cat]) {
        providers[cat].forEach((p) => {
          const icono = crearIcono(p);
          container.appendChild(icono);
        });
      }
    }
  });
  if (providers.link) {
    const link = document.createElement("a");
    link.href = providers.link;
    link.textContent = " Ver más opciones";
    link.target = "_blank";
    container.appendChild(link);
  }
}
