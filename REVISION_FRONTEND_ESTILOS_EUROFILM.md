# Revision visual frontend de Eurofilm

Fecha: 2026-06-08  
Alcance: CSS y pantallas visuales de peliculas, series, detalle/card, login, registro y perfil.  
Archivos revisados: `public/css/*.css` y pantallas principales de `public/`.

Este documento no corrige los estilos actuales. Es un fichero separado para explicar que falla visualmente, definir objetivos de mejora y proponer una base CSS mas coherente.

## Objetivos de interfaz

### Objetivo 1: Crear una identidad visual unica

Eurofilm mezcla varios lenguajes visuales: rojo tipo Netflix, azul claro de cabecera, morados, cards lilas, fondos oscuros, fondos claros y formularios blancos. El resultado no parece una sola aplicacion, sino varias pantallas hechas en momentos distintos.

Propuesta:

- Usar una paleta principal sobria: fondo claro, texto oscuro, rojo como acento de cine y azul solo como apoyo.
- Declarar todos los colores en `:root`.
- Evitar que cada CSS redefina una paleta distinta.

### Objetivo 2: Usar medidas consistentes

Ahora hay muchos valores sueltos: `8px`, `10px`, `12px`, `18px`, `20px`, `24px`, `40px`, `50px`, `70px`, `100px`, `320px`, etc. No todos son malos, pero no forman un sistema.

Propuesta:

- Espaciado basado en escala: `4`, `8`, `12`, `16`, `24`, `32`, `48`.
- Radio de borde moderado: `8px` para cards y formularios, `999px` solo para pills/avatar.
- Max-width de pagina: `1200px`.
- Grid de peliculas con `minmax(160px, 1fr)` o `minmax(180px, 1fr)`.

### Objetivo 3: Mejorar responsive real

Hay media queries, pero incompletas. Algunas pantallas solo cambian columnas, pero no ajustan padding, cabecera, cards, formularios o botones.

Propuesta:

- Mobile-first.
- En movil: una columna, padding `16px`, cards compactas, formularios con `width: 100%`.
- En tablet/escritorio: grids progresivos.

### Objetivo 4: Separar estilos globales de estilos por componente

El proyecto usa selectores muy generales como `body`, `form`, `h1`, `h2`, `input`, `label`, `a`. Esto provoca que una pantalla afecte a otra.

Propuesta:

- Global solo para reset, variables, tipografia y layout base.
- Componentes con clases: `.app-header`, `.movie-grid`, `.media-card`, `.auth-form`, `.profile-panel`.
- Evitar aplicar estilos a todos los `form` o todos los `h2`.

### Objetivo 5: Mejorar legibilidad y jerarquia

Algunas pantallas tienen mucho efecto visual pero poca jerarquia: sombras fuertes, degradados, fondos mezclados y cards con colores que compiten con el contenido.

Propuesta:

- Fondo neutro.
- Cards blancas.
- Titulos claros.
- Botones de accion reconocibles.
- Menos sombras grandes.
- Mas espacio entre bloques.

## Fallos visuales detectados

### 1. Variables duplicadas y sin sistema

`global.css` y `profile.css` repiten variables como:

```css
--bg-color
--card-bg
--text-color
--accent-color
--accent-glow
--bg-main
```

Ademas, `favoritos_1.css` define otra paleta diferente:

```css
--primary: #1d3557;
--accent: #e63946;
```

Problema: cada pantalla parece de una aplicacion distinta.

Mejora: una sola fuente de variables en `global.css`.

### 2. Selectores globales peligrosos

`auth.css` aplica estilos directamente a `form`, `label`, `input` y `a`. Eso es comodo, pero si una pagina carga `auth.css`, puede alterar cualquier formulario o enlace.

Ejemplo:

```css
form {
    width: 360px;
}
```

Problema: un formulario de perfil, filtros o busqueda podria heredar reglas no deseadas.

Mejora:

```css
.auth-form {
    width: min(100%, 380px);
}

.auth-form input {
    width: 100%;
}
```

### 3. Estilos duplicados dentro del mismo archivo

En `auth.css`, `form` se declara varias veces con reglas contradictorias:

```css
form {
    max-width: 420px;
    background: rgba(255, 255, 255, 0.04);
    border-radius: 20px;
}

form {
    background-color: #ffffff;
    border-radius: 10px;
    width: 360px;
}
```

Problema: el segundo bloque pisa al primero. Esto dificulta saber que estilo es real.

Mejora: una sola declaracion por componente.

### 4. Hover aplicado siempre en series

En `style-series.css` aparece:

```css
.serie {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.5);
}
```

Problema: todas las series aparecen permanentemente elevadas, como si estuvieran en hover. Falta `:hover`.

Deberia ser:

```css
.serie:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.16);
}
```

### 5. Pantalla detalle/card con fondo oscuro pero contenido claro

`tarjeta-peli.css` y `card.css` ponen:

```css
body {
    padding: 40px;
    background-color: #141414;
}
```

Pero dentro hay paneles blancos, texto negro, cards translúcidas y boton rojo. El contraste entre fondo oscuro y bloques claros no esta mal por si mismo, pero aqui no se coordina con el resto de pantallas, que usan fondo claro.

Problemas:

- El padding en `body` puede romper cabecera/footer.
- El detalle no comparte sistema visual con listado.
- Hay radios muy grandes (`20px`, `50px`, `70px`) sin criterio comun.

Mejora:

- Quitar padding global de `body`.
- Aplicar padding a `.page-shell` o `main`.
- Usar grid detalle con poster fijo y contenido flexible.

### 6. Cards de peliculas poco profesionales

`style.css` usa:

```css
--card-bg: #c0beee;
```

Problema: las tarjetas lilas hacen que posters, titulos y ratings compitan visualmente. En una app de cine, el poster debe ser el protagonista.

Mejora:

- Card blanca o gris muy claro.
- Poster con proporcion estable.
- Titulo con dos lineas maximo.
- Rating como badge pequeno.

### 7. Header muy dominante y poco integrado

`header.css` usa un azul saturado:

```css
background-color: #5cb5f1;
border-bottom: 3px solid #e50914;
```

Problema: la cabecera parece de una app diferente a las cards, al footer y a login. Tambien aplica `body` y `h1`, lo cual no deberia estar dentro de header.

Mejora:

- Header blanco o muy oscuro.
- Borde inferior suave.
- Logo y navegacion alineados.
- Buscar contraste y foco, no saturacion.

### 8. Login y register tienen buena base, pero medidas rigidas

`auth.css` usa:

```css
width: 360px;
padding: 2rem;
```

Problema: en movil estrecho puede quedar justo. Ademas, register tiene mas campos y necesita mejor separacion.

Mejora:

```css
width: min(100%, 400px);
padding: clamp(20px, 4vw, 32px);
```

### 9. Perfil parece formulario administrativo, no pantalla de producto

`profile.css` es funcional, pero visualmente plano. Hay datos de usuario, formulario, reviews y favoritos. Falta estructura clara.

Problemas:

- El formulario ocupa demasiado protagonismo.
- No hay separacion visual entre perfil, reviews y favoritos.
- Botones tienen colores correctos, pero no comparten sistema con el resto.

Mejora:

- Panel superior de usuario.
- Secciones separadas con `.section`.
- Formulario con grid de dos columnas en escritorio.
- Botones consistentes.

### 10. Falta accesibilidad visual

Problemas:

- No hay estados `:focus-visible` claros.
- Algunos textos usan grises muy suaves.
- Los botones dependen solo de color.
- Hay transformaciones hover fuertes.

Mejora:

- Añadir foco visible.
- Usar contraste estable.
- Reducir animaciones.
- Respetar `prefers-reduced-motion`.

## Medidas CSS recomendadas

### Contenedor

```css
.page-shell {
  width: min(100% - 32px, 1200px);
  margin-inline: auto;
  padding-block: 24px 48px;
}
```

### Grid peliculas/series

```css
.media-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 20px;
}

@media (min-width: 768px) {
  .media-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 24px;
  }
}
```

### Card poster

```css
.media-card__poster {
  aspect-ratio: 2 / 3;
  width: 100%;
  object-fit: cover;
}
```

### Formulario

```css
.auth-form {
  width: min(100%, 400px);
  padding: clamp(20px, 4vw, 32px);
}
```

### Detalle de pelicula/serie

```css
.detail-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr);
  gap: 24px;
}

@media (min-width: 900px) {
  .detail-layout {
    grid-template-columns: minmax(0, 1fr) 320px;
    align-items: start;
  }
}
```

## CSS base propuesto

Este CSS podria convertirse en un futuro archivo, por ejemplo `public/css/eurofilm-ui.css`, pero aqui se deja como propuesta para no modificar la app.

```css
/* eurofilm-ui.css - propuesta de sistema visual */

:root {
  --ef-bg: #f6f7fb;
  --ef-surface: #ffffff;
  --ef-surface-soft: #f1f5f9;
  --ef-text: #111827;
  --ef-muted: #64748b;
  --ef-border: #dbe3ee;
  --ef-primary: #d91f2a;
  --ef-primary-hover: #b91721;
  --ef-secondary: #1d4ed8;
  --ef-success: #15803d;
  --ef-warning: #b45309;
  --ef-danger: #dc2626;

  --ef-radius-sm: 6px;
  --ef-radius-md: 8px;
  --ef-radius-lg: 12px;
  --ef-radius-pill: 999px;

  --ef-shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.08);
  --ef-shadow-md: 0 8px 24px rgba(15, 23, 42, 0.10);

  --ef-space-1: 4px;
  --ef-space-2: 8px;
  --ef-space-3: 12px;
  --ef-space-4: 16px;
  --ef-space-5: 24px;
  --ef-space-6: 32px;
  --ef-space-7: 48px;

  --ef-container: 1200px;
}

*,
*::before,
*::after {
  box-sizing: border-box;
}

html {
  color-scheme: light;
}

body {
  margin: 0;
  min-height: 100vh;
  background: var(--ef-bg);
  color: var(--ef-text);
  font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
  line-height: 1.5;
}

img {
  max-width: 100%;
  display: block;
}

a {
  color: inherit;
}

button,
input,
select,
textarea {
  font: inherit;
}

:focus-visible {
  outline: 3px solid rgba(29, 78, 216, 0.35);
  outline-offset: 2px;
}

.page-shell {
  width: min(100% - 32px, var(--ef-container));
  margin-inline: auto;
  padding-block: var(--ef-space-5) var(--ef-space-7);
}

.section-title {
  margin: 0 0 var(--ef-space-5);
  font-size: clamp(1.35rem, 2vw, 1.8rem);
  line-height: 1.15;
  font-weight: 800;
  letter-spacing: 0;
}

.section-title::after {
  content: "";
  display: block;
  width: 44px;
  height: 3px;
  margin-top: 10px;
  border-radius: var(--ef-radius-pill);
  background: var(--ef-primary);
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 40px;
  gap: var(--ef-space-2);
  padding: 0 var(--ef-space-4);
  border: 1px solid transparent;
  border-radius: var(--ef-radius-md);
  background: var(--ef-surface);
  color: var(--ef-text);
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 160ms ease, border-color 160ms ease, transform 160ms ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.btn-primary {
  background: var(--ef-primary);
  color: #ffffff;
}

.btn-primary:hover {
  background: var(--ef-primary-hover);
}

.btn-secondary {
  border-color: var(--ef-border);
  background: var(--ef-surface);
}

.btn-danger {
  background: var(--ef-danger);
  color: #ffffff;
}

@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    scroll-behavior: auto !important;
    transition-duration: 0.01ms !important;
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
  }
}
```

## CSS propuesto para header

```css
.app-header {
  position: sticky;
  top: 0;
  z-index: 20;
  background: rgba(255, 255, 255, 0.94);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--ef-border);
}

.app-header__inner {
  width: min(100% - 32px, var(--ef-container));
  min-height: 72px;
  margin-inline: auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--ef-space-4);
}

.app-brand {
  display: inline-flex;
  align-items: center;
  gap: var(--ef-space-3);
  color: var(--ef-text);
  text-decoration: none;
  font-weight: 800;
}

.app-brand__logo {
  width: 44px;
  height: 44px;
  border-radius: var(--ef-radius-pill);
  object-fit: cover;
}

.app-nav {
  display: flex;
  align-items: center;
  gap: var(--ef-space-2);
  flex-wrap: wrap;
}

.app-nav a {
  padding: 8px 10px;
  border-radius: var(--ef-radius-md);
  color: var(--ef-muted);
  text-decoration: none;
  font-weight: 700;
}

.app-nav a:hover,
.app-nav a[aria-current="page"] {
  color: var(--ef-primary);
  background: #fff1f2;
}

@media (max-width: 700px) {
  .app-header__inner {
    min-height: auto;
    padding-block: var(--ef-space-3);
    flex-direction: column;
    align-items: stretch;
  }

  .app-nav {
    overflow-x: auto;
    flex-wrap: nowrap;
    padding-bottom: 2px;
  }
}
```

## CSS propuesto para peliculas y series

```css
.media-toolbar {
  display: grid;
  gap: var(--ef-space-3);
  margin-bottom: var(--ef-space-5);
}

@media (min-width: 760px) {
  .media-toolbar {
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: end;
  }
}

.media-search {
  display: flex;
  gap: var(--ef-space-2);
}

.media-search input,
.media-filter select {
  min-height: 42px;
  width: 100%;
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-md);
  padding: 0 var(--ef-space-3);
  background: var(--ef-surface);
  color: var(--ef-text);
}

.media-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: var(--ef-space-5);
}

@media (min-width: 768px) {
  .media-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  }
}

.media-card {
  min-width: 0;
  overflow: hidden;
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-lg);
  background: var(--ef-surface);
  box-shadow: var(--ef-shadow-sm);
  transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease;
}

.media-card:hover {
  transform: translateY(-4px);
  border-color: rgba(217, 31, 42, 0.35);
  box-shadow: var(--ef-shadow-md);
}

.media-card__link {
  display: block;
  color: inherit;
  text-decoration: none;
}

.media-card__poster {
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  background: var(--ef-surface-soft);
}

.media-card__body {
  display: grid;
  gap: var(--ef-space-2);
  padding: var(--ef-space-3);
}

.media-card__title {
  margin: 0;
  min-height: 2.6em;
  color: var(--ef-text);
  font-size: 0.95rem;
  line-height: 1.3;
  font-weight: 800;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.media-card__meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--ef-space-2);
  color: var(--ef-muted);
  font-size: 0.82rem;
}

.rating-badge {
  display: inline-flex;
  align-items: center;
  min-height: 24px;
  padding: 0 8px;
  border-radius: var(--ef-radius-pill);
  background: #fff7ed;
  color: var(--ef-warning);
  font-size: 0.78rem;
  font-weight: 800;
}
```

## CSS propuesto para detalle/card

```css
.detail-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr);
  gap: var(--ef-space-5);
}

@media (min-width: 920px) {
  .detail-layout {
    grid-template-columns: minmax(0, 1fr) 320px;
    align-items: start;
  }
}

.detail-main,
.detail-aside,
.detail-section {
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-lg);
  background: var(--ef-surface);
  box-shadow: var(--ef-shadow-sm);
}

.detail-main {
  padding: clamp(20px, 4vw, 32px);
}

.detail-aside {
  overflow: hidden;
}

.detail-poster {
  width: 100%;
  aspect-ratio: 2 / 3;
  object-fit: cover;
  background: var(--ef-surface-soft);
}

.detail-aside__body {
  display: grid;
  gap: var(--ef-space-3);
  padding: var(--ef-space-4);
}

.detail-title {
  margin: 0 0 var(--ef-space-3);
  font-size: clamp(1.6rem, 3vw, 2.4rem);
  line-height: 1.1;
  font-weight: 900;
}

.detail-meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--ef-space-2);
  margin-bottom: var(--ef-space-4);
}

.detail-pill {
  display: inline-flex;
  align-items: center;
  min-height: 28px;
  padding: 0 10px;
  border-radius: var(--ef-radius-pill);
  background: var(--ef-surface-soft);
  color: var(--ef-muted);
  font-size: 0.82rem;
  font-weight: 700;
}

.detail-overview {
  max-width: 70ch;
  color: #334155;
  font-size: 1rem;
}

.cast-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
  gap: var(--ef-space-4);
  padding: 0;
  list-style: none;
}

.cast-card {
  min-width: 0;
  text-align: center;
}

.cast-card img {
  width: 72px;
  height: 72px;
  margin-inline: auto;
  border-radius: var(--ef-radius-pill);
  object-fit: cover;
}

.cast-card__name {
  margin-top: var(--ef-space-2);
  font-size: 0.84rem;
  font-weight: 800;
}

.cast-card__role {
  color: var(--ef-muted);
  font-size: 0.75rem;
}
```

## CSS propuesto para login y register

```css
.auth-page {
  min-height: calc(100vh - 72px);
  display: grid;
  place-items: center;
  padding: var(--ef-space-5) var(--ef-space-4);
}

.auth-form {
  width: min(100%, 400px);
  padding: clamp(20px, 4vw, 32px);
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-lg);
  background: var(--ef-surface);
  box-shadow: var(--ef-shadow-md);
}

.auth-form__title {
  margin: 0 0 var(--ef-space-5);
  font-size: 1.6rem;
  line-height: 1.15;
  font-weight: 900;
}

.form-field {
  display: grid;
  gap: var(--ef-space-2);
  margin-bottom: var(--ef-space-4);
}

.form-field label {
  color: var(--ef-text);
  font-size: 0.9rem;
  font-weight: 800;
}

.form-field input,
.form-field textarea,
.form-field select {
  min-height: 44px;
  width: 100%;
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-md);
  padding: 0 var(--ef-space-3);
  background: #ffffff;
  color: var(--ef-text);
}

.form-field textarea {
  min-height: 120px;
  padding-block: var(--ef-space-3);
  resize: vertical;
}

.form-help,
.auth-link {
  color: var(--ef-muted);
  font-size: 0.9rem;
}

.auth-link a {
  color: var(--ef-primary);
  font-weight: 800;
  text-decoration: none;
}

.form-error {
  margin: 0 0 var(--ef-space-4);
  padding: var(--ef-space-3);
  border-radius: var(--ef-radius-md);
  background: #fef2f2;
  color: var(--ef-danger);
  font-weight: 700;
}
```

## CSS propuesto para perfil

```css
.profile-layout {
  display: grid;
  gap: var(--ef-space-5);
}

.profile-card {
  display: grid;
  gap: var(--ef-space-4);
  padding: clamp(20px, 4vw, 32px);
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-lg);
  background: var(--ef-surface);
  box-shadow: var(--ef-shadow-sm);
}

@media (min-width: 800px) {
  .profile-card {
    grid-template-columns: 220px minmax(0, 1fr);
  }
}

.profile-summary {
  display: grid;
  align-content: start;
  gap: var(--ef-space-2);
}

.profile-name {
  margin: 0;
  font-size: 1.4rem;
  font-weight: 900;
}

.profile-username {
  color: var(--ef-muted);
  font-weight: 700;
}

.profile-form {
  display: grid;
  gap: var(--ef-space-4);
}

@media (min-width: 760px) {
  .profile-form {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .profile-form .form-field--wide {
    grid-column: 1 / -1;
  }
}

.profile-actions {
  display: flex;
  gap: var(--ef-space-3);
  flex-wrap: wrap;
}

.content-section {
  padding: clamp(18px, 3vw, 24px);
  border: 1px solid var(--ef-border);
  border-radius: var(--ef-radius-lg);
  background: var(--ef-surface);
  box-shadow: var(--ef-shadow-sm);
}
```

## Cambios concretos recomendados por archivo

### `global.css`

Objetivo: convertirlo en el unico sitio de variables, reset, tipografia, contenedor y botones base.

Acciones:

- Dejar `:root` solo aqui.
- Quitar variables duplicadas de otros CSS.
- Añadir `:focus-visible`.
- Añadir `.page-shell`, `.section-title`, `.btn`.

### `header.css`

Objetivo: que el header sea un componente, no un reset global.

Acciones:

- Quitar estilos de `body` y `h1`.
- Cambiar selectores a `.app-header`, `.app-nav`, `.app-brand`.
- Reducir saturacion del azul.
- Mejorar mobile con navegacion horizontal o menu.

### `style.css` y `style-series.css`

Objetivo: unificar peliculas y series.

Acciones:

- Crear una clase comun `.media-grid`.
- Crear una clase comun `.media-card`.
- Corregir `.serie` para que el `transform` solo ocurra en `:hover`.
- Evitar fondo lila en cards.

### `tarjeta-peli.css` y `card.css`

Objetivo: unificar detalle de pelicula y serie.

Acciones:

- Eliminar `body { padding: 40px; background-color: #141414; }`.
- Crear `.detail-layout`, `.detail-main`, `.detail-aside`.
- Usar poster con `aspect-ratio: 2 / 3`.
- Reducir radios y sombras.
- Evitar duplicacion casi completa entre ambos archivos.

### `auth.css`

Objetivo: formularios limpios y no invasivos.

Acciones:

- Sustituir `form`, `input`, `label`, `a` por `.auth-form`, `.form-field`, `.auth-link`.
- Usar `width: min(100%, 400px)`.
- Corregir `form.button:hover`, que no selecciona el boton. Deberia ser `form button:hover` o `.auth-form .btn-primary:hover`.

### `profile.css`

Objetivo: convertir perfil en pantalla organizada.

Acciones:

- Quitar variables duplicadas.
- Usar paneles: resumen, formulario, reviews, favoritos.
- Formulario en dos columnas en escritorio.
- No depender de `h1::after` si la pantalla usa `h2`.

### `favoritos_1.css`, `favoritos_2.css`, `resena.css`

Objetivo: que favoritos y reviews parezcan parte del mismo sistema.

Acciones:

- Unificar cards con `.media-card` y `.content-section`.
- Evitar `height: 150px` fijo en reviews si el texto puede variar.
- Mantener `line-clamp`, pero permitir que la card crezca en pantallas estrechas.

## Prioridad de mejora

1. Crear sistema visual en `global.css`.
2. Unificar cards de peliculas y series.
3. Corregir `style-series.css` porque aplica hover permanente.
4. Rehacer `auth.css` con clases propias.
5. Quitar estilos globales de `header.css`.
6. Unificar `tarjeta-peli.css` y `card.css`.
7. Mejorar perfil con layout por secciones.

## Conclusion visual

La aplicacion tiene contenido suficiente, pero el frontend falla por falta de sistema. No es un problema de "hacerlo mas bonito" solamente: los estilos actuales se pisan entre si, usan medidas rigidas, mezclan paletas y hacen que cada pantalla tenga una identidad diferente.

La mejora mas importante seria crear un pequeño sistema CSS propio de Eurofilm y aplicarlo por componentes. Con una paleta unica, grids estables, cards coherentes, formularios encapsulados y responsive real, el proyecto se veria mucho mas profesional sin cambiar la logica PHP ni la API.
