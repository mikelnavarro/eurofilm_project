
// Referencias
let paginaActual = 1;
const movieContainer = document.getElementById("peliculas");



// Función genérica para peticiones al puente PHP
async function request(params) {
    try {
        const queryParams = new URLSearchParams(params).toString();
        const res = await fetch(`movie/index?${queryParams}`);
        const data = await response.json();
        return data.results; // TMDB devuelve los resultados en .results
        paginaActual++;

    } catch (error) {
        console.error("Error en la petición:", error);
        return null;
    }
}
async function fetchMovies(pagina) {
    try {
        const res = await fetch(`movie/index?page=${pagina}`);
        if (!res.ok) throw new Error("Error en la red");

        const data = await res.json();
        return data.results; // Solo devolvemos los datos puros
    } catch (error) {
        console.error("Error en la petición:", error);
        return null;
    }
}
function renderList(movies) {
    if (!movies) return;
    // Limpiamos el contenedor de forma eficiente
    movieContainer.innerHTML = "";
    movieContainer.style.display = "grid";

    movies.forEach((movie) => {
        // Creamos la estructura de la Card
        const card = createMovieCard(movie);
        movieContainer.appendChild(card);
    });
}
// función de carga de detalles de Movies
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

    // ID de la película (como pedías en tu código anterior)
    const id = document.createElement("span");
    id.textContent = movie.id;

    // Año de lanzamiento
    const releaseYear = document.createElement("p");
    releaseYear.textContent = movie.release_date ? movie.release_date.slice(0, 4) : "—";

    // Añadimos todo a la tarjeta
    card.append(img, title, id, releaseYear);

    return card;
}
/**
 * 4. FUNCIÓN DE INICIO (Orquestadora)
 */
async function init() {
    const movies = await fetchMovies(paginaActual);
    renderList(movies);
}

