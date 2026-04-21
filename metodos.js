import { TMDB_CONFIG } from "./config.js";
// Metodos a exportar

// 2. SERVICIO DE DATOS (Fetch puro)
export async function getMovies(endpoint, extraParams = {}) {
  try {
    // Construimos los parámetros de la URL combinando los fijos (language, region)
    // con los opcionales que lleguen (page, query, etc.)
    const params = new URLSearchParams({
      ...TMDB_CONFIG.PARAMS,
      ...extraParams,
    });
    // Montamos la URL completa: base + endpoint + parámetros
    // fetch() hace la petición HTTP. El await pausa aquí hasta tener respuesta.

    const response = await fetch(
      `${TMDB_CONFIG.BASE_URL}${endpoint}?${params}`,
      TMDB_CONFIG.OPTIONS,
    );
    // Si respondió 404, 500, etc. lanzamos un error manual con el código.
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }
    const data = await response.json();

    // TMDB devuelve { page, results, total_pages, total_results }
    // Nosotros solo queremos el array de películas.
    return data.results; // Retornamos directamente los resultados
  } catch (error) {
    console.error("getMovies falló:", error);
    return [];
  }
}
// Función para obtener detalles de una película por ID
export async function getMovieDetails(movieId) {
  try {
    const params = new URLSearchParams({ language: "es-ES" });
    const response = await fetch(
      `${TMDB_CONFIG.BASE_URL}/movie/${movieId}?${params}`,
      TMDB_CONFIG.OPTIONS,
    );
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return await response.json();
  } catch (error) {
    console.error(error);
    return null;
  }
}

export async function getGeneros() {
    const data = await fetch(`${TMDB_CONFIG.BASE_URL}/genre/movie/list?${new URLSearchParams(TMDB_CONFIG.PARAMS)}`, TMDB_CONFIG.OPTIONS);
    const json = await data.json();
    return json.genres;
}

export async function getPlataformas() {
    const params = new URLSearchParams({ ...TMDB_CONFIG.PARAMS, watch_region: 'ES' });
    const data = await fetch(`${TMDB_CONFIG.BASE_URL}/watch/providers/movie?${params}`, TMDB_CONFIG.OPTIONS);
    const json = await data.json();
    return json.results;
}