// Configuración
const BASE_URL = "/Eurofilm/api/movies";
let paginaActual = 1;

// 1. Fetch de datos reales (tu API)
async function fetchMovies(pagina) {
    try {
        const res = await fetch(`${BASE_URL}?page=${pagina}`);
        if (!res.ok) throw new Error(`Error HTTP: ${res.status}`);
        
        const data = await res.json();
        console.log("Datos recibidos:", data);
        
        // Devolvemos el array de resultados
        return data.results || [];
    } catch (error) {
        console.error("Error en fetchMovies:", error);
        return [];
    }
}

// 2. Renderizado real sin innerHTML
function renderList(movies) {
    const movieContainer = document.getElementById("peliculas");

    if (!movieContainer) {
        console.error("Error crítico: El ID 'peliculas' no existe en el HTML.");
        return;
    }

    // Limpiamos el contenedor sin usar innerHTML
    while (movieContainer.firstChild) {
        movieContainer.removeChild(movieContainer.firstChild);
    }

    movieContainer.style.display = "grid";

    // Recorremos los datos reales de tu API
    movies.forEach((movie) => {
        const card = document.createElement("div");
        card.className = "pelicula-card";
        card.style.cursor = "pointer";

        card.onclick = () => {
            window.location.href = `card.php?id=${movie.id}`;
        };

        // Imagen
        const img = document.createElement("img");
        img.src = movie.poster_path
            ? `https://tmdb.org{movie.poster_path}`
            : "placeholder.png";
        img.alt = movie.title;

        // Título
        const title = document.createElement("h3");
        title.textContent = movie.title || "Sin título";

        // Año
        const releaseYear = document.createElement("p");
        releaseYear.textContent = movie.release_date ? movie.release_date.slice(0, 4) : "—";

        // Construcción de la card
        card.appendChild(img);
        card.appendChild(title);
        card.appendChild(releaseYear);

        // Inserción en el DOM
        movieContainer.appendChild(card);
    });
}

// 3. Inicialización
document.addEventListener("DOMContentLoaded", async () => {
    console.log("Cargando películas de la API...");
    const movies = await fetchMovies(paginaActual);
    renderList(movies);
});
