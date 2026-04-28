// Referencias
let paginaActual = 1;
const movieContainer = document.getElementById("peliculas");

async function fetchMovies(pagina) {
    try {
        const res = await fetch(`../movie/getAll?page=${pagina}`);

        if (!res.ok) {
            throw new Error(`Error HTTP: ${res.status}`);
        }

        const data = await res.json();
        console.log("Datos recibidos:", data);
        return data.results;
    } catch (error) {
        console.error("Error en fetchMovies:", error);
        return null;
    }
}

function renderList(movies) {
    if (!movies || movies.length === 0) {
        movieContainer.innerHTML = "<h3>No se encuentran películas</h3>";
        return;
    }

    movieContainer.innerHTML = "";
    movieContainer.style.display = "grid";
    movieContainer.style.gridTemplateColumns = "repeat(auto-fill, minmax(150px, 1fr))";
    movieContainer.style.gap = "15px";

    movies.forEach(movie => {
        const card = createMovieCard(movie);
        movieContainer.appendChild(card);
    });
    
    console.log("Películas renderizadas:", movies.length);
}

function createMovieCard(movie) {
    const card = document.createElement("div");
    card.className = "pelicula-card";
    card.style.cursor = "pointer";

    // Navegación al detalle
    card.addEventListener("click", () => {
        window.location.href = `card.php?id=${movie.id}`;
    });

    // Imagen del póster
    const img = document.createElement("img");
    img.src = movie.poster_path
        ? `https://image.tmdb.org/t/p/w200${movie.poster_path}`
        : "placeholder.png";
    img.alt = movie.title;

    // Título de la película
    const title = document.createElement("h3");
    title.textContent = movie.title;

    // ID de la película
    const id = document.createElement("span");
    id.textContent = movie.id;

    // Año de lanzamiento
    const releaseYear = document.createElement("p");
    releaseYear.textContent = movie.release_date ? movie.release_date.slice(0, 4) : "—";

    // Construir tarjeta
    card.append(img, title, id, releaseYear);

    return card;
}

async function init() {
    console.log("Iniciando carga de películas...");
    const movies = await fetchMovies(paginaActual);
    if (movies) {
        renderList(movies);
    } else {
        movieContainer.innerHTML = "<p>No se pudieron cargar las películas.</p>";
    }
}

// Llamar directamente (no necesita DOMContentLoaded gracias a defer)
init();