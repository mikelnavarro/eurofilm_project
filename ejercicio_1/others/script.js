// Otras funciones
/*
fetch(url, options)
  .then(res => res.json())
  .then(data => {
    data.results.forEach(movie => {
      // Creamos la "tarjeta" para cada película
      const card = document.createElement('div');
      card.className = 'card';
      
      // La URL base para imágenes es image.tmdb.org
      const posterPath = movie.poster_path 
        ? `image.tmdb.org{movie.poster_path}` 
        : 'via.placeholder.com';

      card.innerHTML = `
        <img src="${posterPath}" alt="${movie.title}">
        <h3>${movie.title}</h3>
        <p>⭐ ${movie.vote_average.toFixed(1)}</p>
      `;
      grid.appendChild(card);
    });
  })
  .catch(err => console.error('Error cargando películas:', err));

*/