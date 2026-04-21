

// 1. CONFIGURACIÓN (Variables que no cambian)
export const TMDB_CONFIG = {
  BASE_URL: "https://api.themoviedb.org/3",
  PARAMS: { language: "es-ES", region: "ES" },
  IMAGE_URL: "https://image.tmdb.org/t/p/w500",
  OPTIONS: {
    method: "GET",
    headers: {
      accept: "application/json",
      Authorization: `Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZDNmYWVjZDI3ODVkYzM1ZjE2MzVmOGQwM2Q2OWU4ZSIsIm5iZiI6MTc3NjI4MDQyMC43NTIsInN1YiI6IjY5ZGZlMzY0MTY4N2RiZGU1NmY5NDA5NyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.8V7izLrCtRLetzuCAPWnWLW3aUEuvl-LSkdg8KHqhIE`,
    },
  },
};
export const endpoints = {
  popular: "/movie/popular",
  topRated: "/movie/top_rated",
  upcoming: "/movie/upcoming",
  trending: "/trending/movie/week",
  search: "/search/movie",
};