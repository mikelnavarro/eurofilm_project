# Revision tecnica del proyecto Eurofilm

Fecha de revision: 2026-06-08  
Proyecto revisado: `D:\xampp\htdocs\Eurofilm`  
Alcance: verificacion estatica del codigo, estructura, configuracion, seguridad, base de datos y migraciones. No se ha modificado la aplicacion web.

## Resumen ejecutivo

Eurofilm tiene una base reconocible de aplicacion PHP con Composer, namespaces, controladores, modelos, servicio para TMDB, vistas publicas y uso frecuente de PDO con parametros enlazados. Eso es positivo para un proyecto academico porque ya hay una separacion inicial entre entrada HTTP, logica de datos y consumo de API externa.

El problema principal no es que el proyecto "no funcione", sino que mezcla patrones buenos con patrones peligrosos o incompletos. Para entregar con mas solvencia, el estudiante deberia priorizar estos puntos:

1. Retirar secretos reales del repositorio y regenerar el token de TMDB.
2. Corregir acciones POST sin proteccion CSRF.
3. Escapar toda salida HTML y evitar `innerHTML` con datos de usuarios o API.
4. Convertir el dump SQL en migraciones versionadas reproducibles.
5. Corregir bugs funcionales detectados en favoritos, perfil, reviews y mailer.
6. Unificar arquitectura: rutas MVC o paginas PHP directas, pero no ambas sin criterio.
7. Mejorar documentacion de instalacion, base de datos y variables de entorno.

## Verificaciones realizadas

- `php -l` sobre los ficheros PHP del proyecto: no se detectaron errores de sintaxis.
- `composer validate --no-check-publish`: `composer.json` es valido, con advertencia de falta de licencia.
- Busqueda de patrones de riesgo: `innerHTML`, `var_dump`, `die`, `$_POST`, `$_GET`, `$_SESSION`, secretos, SSL desactivado, migraciones y SQL.
- Revision manual de los puntos de entrada, controladores, modelos, configuracion, servicio TMDB y dump SQL.

## Fuentes verificables consultadas

Estas fuentes estaban disponibles en Internet durante la revision:

- OWASP Secrets Management Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Secrets_Management_Cheat_Sheet.html
- OWASP Cross Site Scripting Prevention Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html
- OWASP Cross-Site Request Forgery Prevention Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html
- OWASP Session Management Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html
- PHP manual `password_hash`: https://www.php.net/manual/en/function.password-hash.php
- PHP manual `session_regenerate_id`: https://www.php.net/manual/en/function.session-regenerate-id.php
- PHP manual `htmlspecialchars`: https://www.php.net/htmlspecialchars
- PHP manual PDO prepared statements: https://www.php.net/manual/en/pdo.prepared-statements.php
- PHP manual `PDOStatement::bindValue`: https://www.php.net/manual/en/pdostatement.bindvalue.php
- Composer schema/autoload PSR-4: https://getcomposer.org/doc/04-schema.md
- Composer basic usage/autoload: https://getcomposer.org/doc/01-basic-usage.md
- Composer scripts: https://getcomposer.org/doc/articles/scripts.md
- MariaDB foreign key constraints: https://mariadb.com/docs/server/architecture/server-constraints/foreign-key-constraints
- MariaDB constraint reference: https://mariadb.com/docs/server/reference/sql-statements/data-definition/constraint
- Doctrine Migrations documentation, como referencia de patron de migraciones en PHP: https://www.doctrine-project.org/projects/doctrine-migrations/en/current/
- Doctrine Migrations, generacion de migraciones: https://www.doctrine-project.org/projects/doctrine-migrations/en/current/reference/generating-migrations.html
- Phinx, herramienta de migraciones PHP instalable con Composer: https://phinx.org/
- Phinx documentation: https://book.cakephp.org/phinx/
- Laravel migrations, como referencia de framework con migraciones integradas: https://laravel.com/docs/migrations
- MDN Fetch API y credenciales/cookies: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch
- MDN Request credentials: https://developer.mozilla.org/en-US/docs/Web/API/Request/credentials
- PHP manual de sesiones: https://www.php.net/manual/en/book.session.php
- Apache Server Side Includes: https://httpd.apache.org/docs/2.4/en/howto/ssi.html
- Apache `mod_include`: https://httpd.apache.org/docs/current/mod/mod_include.html

No ha sido necesario usar libros porque existen fuentes oficiales y actuales suficientes. Si se quisiera apoyar con bibliografia, una referencia clasica seria *Web Application Security* de Andrew Hoffman, ISBN 9781492053118.

## Estructura detectada

Estructura principal:

- `public/`: paginas PHP, CSS, JS, imagenes y `index.php`.
- `src/core/`: router basico, controlador base y conexion a base de datos.
- `src/controllers/`: controladores de autenticacion, peliculas y API TMDB.
- `src/models/`: modelos para usuarios, peliculas, listas y reviews.
- `src/services/`: servicio `TmdbService`.
- `src/databases/eurofilm_db.sql`: dump completo de phpMyAdmin.
- `tools/`: clase `Mailer`.
- `vendor/`: dependencias Composer.
- `.env`: configuracion con credenciales reales.

Patron esperado por `composer.json`:

```json
"autoload": {
  "psr-4": {
    "Mikelnavarro\\Eurofilm\\": "src/"
  }
}
```

Este patron es correcto en intencion: Composer recomienda mapear namespaces a directorios mediante PSR-4. El problema es que el proyecto no lo aplica de forma completamente coherente.

## Patrones correctos encontrados

### 1. Uso de Composer y autoload PSR-4

`composer.json` define un namespace raiz para `src/`. Esto es buena practica porque evita `require` manuales por todas partes y permite cargar clases de forma consistente.

Estado: correcto, pero incompleto.

Problema asociado: hay diferencias de mayusculas en namespaces (`core` frente a `Core`) y aun existen muchas paginas PHP en `public/` que incluyen ficheros manualmente.

### 2. Uso de PDO con consultas preparadas

`src/core/Db.php` centraliza `prepare`, `bindValue`, `execute`, `fetch` y `fetchAll`. Los modelos usan parametros como `:email`, `:username`, `:movie_id`.

Estado: correcto.

Razon: la documentacion de PDO y `PDOStatement::bindValue` confirma que las consultas preparadas son el patron adecuado para enlazar valores de entrada y reducir riesgo de SQL injection.

### 3. Hash de contrasenas

`src/models/Usuario.php` usa:

```php
password_hash($pass, PASSWORD_BCRYPT)
```

y el login usa `password_verify`.

Estado: correcto en concepto.

Mejora recomendada: usar `PASSWORD_DEFAULT` salvo que se quiera justificar bcrypt explicitamente. El manual de PHP indica que `password_verify` puede verificar hashes generados por `password_hash` sin guardar sal o algoritmo por separado.

### 4. Separacion inicial MVC

Hay controladores (`AuthController`, `MovieController`, `ApiMovieController`), modelos (`Usuario`, `Movie`, `Lista`, `Review`) y servicios (`TmdbService`).

Estado: parcialmente correcto.

El estudiante ya ha intentado separar responsabilidades. Falta cerrar el patron, porque las paginas de `public/` siguen conteniendo logica de sesion, HTML y acoplamientos directos.

## Patrones erroneos o peligrosos

### Critico 1. Secretos reales versionados en `.env`

Archivo afectado: `.env`

Se observan credenciales de base de datos y token/API key de TMDB en texto plano:

- `DB_USER`
- `DB_PASS`
- `API_KEY`
- `TOKEN_TMDB`

Riesgo:

- Cualquiera con acceso al repositorio puede usar esas credenciales.
- Si el repositorio se sube a GitHub, GitLab, aula virtual o se comparte en ZIP, el secreto ya debe considerarse filtrado.
- El token de TMDB deberia revocarse y regenerarse.

Patron correcto:

- Mantener `.env` fuera de Git.
- Incluir `.env.example` sin secretos reales.
- Documentar como obtener y configurar claves.
- Rotar cualquier secreto ya expuesto.

Fuente: OWASP Secrets Management advierte contra secretos hardcodeados o almacenados en texto plano dentro de ficheros de configuracion del codigo.

Prioridad: maxima antes de entregar.

### Critico 2. Acciones POST sin CSRF

Archivos afectados:

- `src/controllers/AuthController.php`
- `src/controllers/MovieController.php`
- `public/js/detalles.js`
- `public/js/favorites.js`
- `public/js/rate.js`
- `public/js/updatePerfil.js`
- `public/js/visibility.js`

Ejemplos:

- `MovieController::addFavorite`
- `MovieController::removeFavorite`
- `MovieController::addReview`
- `MovieController::deleteReview`
- `AuthController::updateProfile`
- `AuthController::logout`

Riesgo:

Un sitio externo podria provocar que el navegador de un usuario autenticado envie peticiones POST a Eurofilm usando sus cookies de sesion. Eso permitiria cambiar perfil, anadir favoritos, borrar reviews o modificar visibilidad sin consentimiento.

Patron correcto:

- Generar un token CSRF por sesion.
- Incluirlo en formularios y peticiones `fetch`.
- Validarlo en cada accion con efectos.
- Responder `403` si falta o no coincide.

Fuente: OWASP CSRF Prevention Cheat Sheet recomienda patrones como Synchronizer Token Pattern.

Prioridad: maxima.

### Critico 3. Riesgo XSS por `innerHTML` con datos no confiables

Archivos afectados:

- `public/js/reviews.js`
- `public/js/favorites.js`
- `public/js/verPerfil.js`
- `public/js/users.js`
- otros renderizadores con `innerHTML`

Ejemplo en `public/js/reviews.js`:

```js
container.innerHTML += `
  <div class="review-card">
    <h3>${review.title ?? ''}</h3>
    <p>${review.comment ?? ''}</p>
    <small>${review.username} · ${review.rating}/5</small>
  </div>
`;
```

`review.title`, `review.comment` y `review.username` vienen de base de datos. Si alguien guarda HTML o JavaScript en una review, podria ejecutarse en el navegador de otros usuarios.

Ejemplo en `public/js/favorites.js`:

```js
<h3>${movie.title}</h3>
```

Aunque venga de TMDB, sigue siendo dato externo. No debe insertarse como HTML.

Patron correcto:

- Usar `textContent` para texto.
- Crear nodos con `document.createElement`.
- Si se necesita HTML real, sanitizarlo con una libreria especifica.
- En PHP, escapar salida con `htmlspecialchars` en todo valor dinamico.

Fuente: OWASP XSS Prevention identifica `innerHTML` como sink peligroso y recomienda codificacion de salida contextual.

Prioridad: maxima.

### Alto 4. Salida PHP sin escape en vistas

Archivo afectado: `public/pantalla/perfil.php`

Ejemplos:

```php
<?php echo $usuario['nombre'] ?? 'No definido'; ?>
<?php echo $usuario['username'] ?? 'No definido'; ?>
<input value="<?php echo $usuario['email'] ?? 'No definido'; ?>">
```

Riesgo:

Si un usuario modifica su nombre, username, email o biografia con caracteres HTML, la pagina puede renderizar contenido no deseado o romper atributos.

Patron correcto:

```php
<?= htmlspecialchars($valor ?? 'No definido', ENT_QUOTES, 'UTF-8') ?>
```

Fuente: manual de PHP `htmlspecialchars`.

Prioridad: alta.

### Alto 5. SSL desactivado en llamadas a TMDB

Archivo afectado: `src/services/TmdbService.php`

Codigo:

```php
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
```

Riesgo:

La aplicacion desactiva la verificacion TLS. Esto permite ataques man-in-the-middle y respuestas manipuladas si la red o el entorno son comprometidos.

Patron correcto:

- No desactivar `CURLOPT_SSL_VERIFYPEER`.
- No desactivar `CURLOPT_SSL_VERIFYHOST`.
- Configurar certificados CA correctamente en XAMPP/PHP si hay error local.
- Gestionar errores de cURL y HTTP.

Prioridad: alta.

### Alto 6. Sesion inconsistente y sin regeneracion tras login

Archivos afectados:

- `public/index.php`
- `public/inc/header.php`
- `src/core/Controller.php`
- `src/controllers/AuthController.php`
- `public/movies/card.php`

Problemas:

- Se llama `session_start()` en varios lugares.
- El metodo `Controller::isLogged()` comprueba `$_SESSION['user_id']`, pero la aplicacion usa `$_SESSION['usuario']`.
- En login/registro no se ve `session_regenerate_id(true)`.
- Algunas paginas leen `$_SESSION['usuario']['id']` sin garantizar que exista.

Ejemplo peligroso:

```php
<div data-resena="<?= $_SESSION['usuario']['id'] ?>" ...></div>
```

en `public/movies/card.php`. Si el usuario no esta autenticado, puede generar warning o comportamiento inconsistente.

Patron correcto:

- Centralizar inicio de sesion.
- Usar una sola clave de sesion: por ejemplo `$_SESSION['usuario']`.
- Regenerar ID de sesion despues de login y registro.
- Proteger vistas privadas antes de renderizar.

Fuente: OWASP Session Management recomienda renovar/regenerar el ID tras cambios de privilegio como autenticacion. PHP ofrece `session_regenerate_id`.

Prioridad: alta.

### Alto 7. Migraciones inexistentes: hay un dump, no un sistema de cambios

Archivo actual: `src/databases/eurofilm_db.sql`

No existe carpeta `migrations/` ni ficheros SQL versionados. El fichero actual es un dump de phpMyAdmin con:

- `CREATE TABLE`
- `INSERT INTO` con datos
- `ALTER TABLE`
- `AUTO_INCREMENT`
- claves foraneas
- datos personales de ejemplo

Problemas:

- No permite saber que cambio se hizo, cuando y por que.
- Mezcla estructura con datos.
- Incluye emails y nombres reales o de apariencia real.
- Puede fallar al reimportar si ya existen tablas.
- No separa schema inicial, seeds y cambios incrementales.

Patron correcto:

- Crear `migrations/`.
- Usar nombres ordenables: `001_create_users.sql`, `002_create_movies.sql`, etc.
- Separar schema de datos semilla.
- Evitar datos personales reales.
- Documentar el orden de ejecucion.

Referencia: Doctrine Migrations es un ejemplo conocido en PHP para versionar cambios de esquema. Aunque este proyecto use SQL manual, el principio es el mismo: cambios pequenos, ordenados y reproducibles.

Prioridad: alta.

### Alto 8. Datos personales y datos de prueba dentro del dump SQL

Archivo afectado: `src/databases/eurofilm_db.sql`

El dump contiene nombres, usernames, emails, paises, biografias y hashes de contrasena. Aunque sean datos de prueba, parecen datos personales reales.

Riesgo:

- Mala practica de privacidad.
- Mala impresion en entrega academica.
- Posible exposicion de informacion personal.

Patron correcto:

- Usar datos ficticios claros: `ana.demo@example.test`.
- No incluir emails reales.
- Separar `schema.sql` de `seed_demo.sql`.
- Mantener minimo de datos para demostrar funcionalidad.

Prioridad: alta.

### Medio 9. Bug en favoritos: se inserta dos veces y con parametros equivocados

Archivo afectado: `src/controllers/MovieController.php`

Flujo actual:

```php
$this->ListModel->addMovie($listId, $movieId);

$ok = $this->ListModel->addMovie(
    $userId,
    $movieId
);
```

Problema:

Primero inserta correctamente con `$listId`. Luego vuelve a insertar usando `$userId` como si fuera `$listId`. Si el id de usuario coincide con una lista, puede insertar en una lista equivocada. Si no coincide, fallara por FK o duplicado.

Patron correcto:

- Llamar una sola vez a `addMovie($listId, $movieId)`.
- Envolver creacion de pelicula/lista/insercion en transaccion si se quiere robustez.
- Retornar claramente `created: true/false`.

Prioridad: media-alta porque rompe funcionalidad.

### Medio 10. Bug JavaScript al eliminar favorito

Archivo afectado: `public/js/favorites.js`

Codigo:

```js
btn.closest(".movie-card").remove();
```

Problema:

`btn` no esta definido. Deberia usarse `e.target`.

Efecto:

La peticion puede borrar en servidor, pero el frontend lanzara error antes de actualizar correctamente la UI.

Prioridad: media.

### Medio 11. `var_dump($_POST)` en endpoint de reviews

Archivo afectado: `src/controllers/MovieController.php`

Codigo:

```php
var_dump($_POST);
```

Problema:

Un endpoint JSON no debe imprimir debug antes de responder JSON. Esto rompe `res.json()` en frontend y puede exponer datos.

Patron correcto:

- Eliminar `var_dump`.
- Registrar errores en logs si hace falta.
- Responder siempre JSON consistente.

Prioridad: media-alta.

### Medio 12. Actualizacion de perfil puede fallar y deja sesion desactualizada

Archivo afectado: `src/controllers/AuthController.php`

Problemas:

- Usa `$_POST['username']`, `$_POST['email']`, `$_POST['nombre']` sin comprobar si existen.
- Si `obtenerUsuarioPorEmail($email)` devuelve `null`, esta linea puede fallar:

```php
if ($email && $emailComprueba->id != $userId) {
```

- Tras actualizar, reconstruye la sesion con `$user`, que es el usuario encontrado antes por username, no necesariamente los datos actualizados.
- Si `$user` es falso, no se envia respuesta JSON final.

Patron correcto:

- Validar campos requeridos.
- Comprobar `if ($emailComprueba && $emailComprueba->id != $userId)`.
- Actualizar y luego volver a leer el usuario por id.
- Responder siempre.

Prioridad: media.

### Medio 13. Mailer tiene un bug de inicializacion

Archivo afectado: `tools/Mailer.php`

Codigo:

```php
$mail = new PHPMailer(true);
$this->config = $config;

$this->mail->isSMTP();
```

Problema:

Se crea `$mail`, pero nunca se asigna a `$this->mail`. La primera llamada a `$this->mail->isSMTP()` intentara usar `null`.

Patron correcto:

```php
$this->mail = new PHPMailer(true);
```

Prioridad: media.

### Medio 14. Namespace inconsistente: `core` frente a `Core`

Archivos afectados:

- `src/core/Controller.php`: `namespace Mikelnavarro\Eurofilm\Core;`
- `src/core/Core.php`: `namespace Mikelnavarro\Eurofilm\core;`
- `src/controllers/AuthController.php`: `use Mikelnavarro\Eurofilm\core\Controller;`
- `src/controllers/MovieController.php`: `use Mikelnavarro\Eurofilm\Core\Controller;`

Problema:

En Windows puede parecer que funciona por tolerancia del sistema de ficheros y del autoload, pero en Linux o en despliegue real las mayusculas/minusculas pueden romper carga de clases. Ademas, no hay criterio uniforme.

Patron correcto:

- Elegir `Core` o `core`, pero usarlo en todos los namespaces, carpetas y `use`.
- Lo habitual en PSR-4 es namespaces en PascalCase para segmentos de clase, por ejemplo `Core`.

Fuente: Composer documenta que PSR-4 mapea namespaces a rutas y clases esperadas.

Prioridad: media.

### Medio 15. Router demasiado permisivo y sin errores HTTP limpios

Archivo afectado: `src/core/Core.php`

Problemas:

- Si el controlador no existe, ejecuta `die`.
- Si el metodo no existe, cae al metodo actual sin respuesta 404 clara.
- Los metodos publicos del controlador quedan potencialmente expuestos si existen.
- No hay middleware de autenticacion/autorizacion.

Patron correcto:

- Responder 404 para controlador/metodo inexistente.
- Definir rutas explicitas o una whitelist de metodos publicos.
- Separar rutas API de vistas.
- Centralizar proteccion de endpoints privados.

Prioridad: media.

### Medio 16. Validacion de entrada insuficiente

Archivos afectados:

- `AuthController.php`
- `MovieController.php`
- `ApiMovieController.php`

Ejemplos:

- `rating` no se valida como numero ni rango.
- `visibility` acepta cualquier texto aunque en DB reviews solo permite `Publica`/`Privada`.
- `tmdb_id`, `movie_id`, `review_id`, `page` no se fuerzan a entero.
- `email` no usa `filter_var($email, FILTER_VALIDATE_EMAIL)`.
- `password` no tiene reglas minimas.

Riesgo:

- Errores de base de datos.
- Estados invalidos.
- Bugs que solo aparecen con entradas maliciosas o incompletas.

Patron correcto:

- Validar formato, tipo y rango en servidor.
- No depender solo del `required` HTML.
- Devolver errores JSON con codigos HTTP adecuados.

Prioridad: media.

### Medio 17. Mezcla de API, vistas y rutas absolutas

Ejemplos:

- JS llama a `/Eurofilm/movie/addFavorite`.
- Vistas estan en `/Eurofilm/public/movies/card.php`.
- `logout` redirige a `/Eurofilm/public/movies/movies.php`.
- `BASE_URL` en JS probablemente acopla rutas locales.

Problema:

La aplicacion queda muy ligada a estar instalada exactamente en `/Eurofilm`. Si se mueve a otro virtual host, subdirectorio o dominio, se rompen rutas.

Patron correcto:

- Definir una base URL unica.
- Generar URLs desde configuracion.
- Preferir rutas relativas a la raiz publica del servidor.
- Documentar configuracion Apache/XAMPP.

Prioridad: media.

### Bajo 18. Documentacion insuficiente y tono inadecuado en `readme-falso.md`

Archivos:

- `README.md`
- `readme-falso.md`

Problemas:

- `README.md` no explica instalacion, variables, BD ni endpoints.
- `readme-falso.md` contiene texto ofensivo hacia docentes.

Recomendacion:

- No entregar `readme-falso.md`.
- Redactar README profesional:
  - descripcion
  - requisitos
  - instalacion
  - configuracion `.env`
  - migraciones
  - ejecucion
  - endpoints principales
  - capturas o funcionalidades

Prioridad: alta para entrega academica, aunque tecnicamente no rompa la app.

### Bajo 19. Codificacion de caracteres rota en varios archivos/dump

Se ven textos como:

- `ConfiguraciÃ³n`
- `PelÃ­culas`
- `AÃ±adir`
- `Â¿Quieres ser mi novia?`

Problema:

Esto indica mojibake: texto UTF-8 interpretado con otra codificacion, o viceversa. Afecta presentacion, busquedas y calidad de entrega.

Patron correcto:

- Guardar todo como UTF-8.
- Declarar `charset=utf8mb4` en MySQL.
- Usar `SET NAMES utf8mb4`.
- Exportar/importar SQL en UTF-8.

Prioridad: media para calidad.

## Revision de base de datos

Tablas detectadas:

- `users`
- `movies`
- `directors`
- `providers`
- `movie_providers`
- `lists`
- `list_movies`
- `reviews`

Aspectos positivos:

- Hay claves primarias.
- Hay `UNIQUE` en `users.email`, `users.username`, `movies.tmdb_id`, `providers.tmdb_id`, `directors.tmdb_id`.
- Hay claves foraneas para listas, peliculas, usuarios y reviews.
- Se usa `utf8mb4`.

Problemas del esquema:

1. `list_movies` define dos constraints equivalentes sobre `list_id`:

```sql
ADD CONSTRAINT `fk_list_movies_list` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_list_movies_listid` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
```

Esto es redundante. MariaDB documenta que las claves foraneas mantienen integridad referencial; duplicar la misma relacion no aporta valor y complica mantenimiento.

2. Faltan restricciones de dominio:

- `reviews.rating` deberia tener `CHECK (rating >= 0 AND rating <= 10)` o el rango real que use la UI.
- `lists.visibility` deberia ser `ENUM('Publica','Privada')` o `VARCHAR` con check.
- `users.role` deberia limitarse a valores conocidos.
- `users.state` deberia tener default claro.

3. Muchas columnas importantes permiten `NULL`:

- `users.email`
- `users.username`
- `movies.tmdb_id`
- `lists.user_id`
- `reviews.user_id`
- `reviews.movie_id`

Algunas pueden ser opcionales por diseno, pero para autenticacion y reviews normalmente deberian ser `NOT NULL`.

4. No hay indice especifico para consultas frecuentes:

- `reviews(user_id, movie_id)` deberia ser unico si solo se permite una review por usuario y pelicula.
- `lists(user_id, title)` deberia ser unico si solo hay una lista `Favoritos` por usuario.

5. El dump incluye datos, no solo estructura.

Para entrega y despliegue, mejor separar `schema` y `seed`.

## Migraciones SQL recomendadas

Por la instruccion de no modificar la aplicacion y escribir solamente un archivo de revision, no se han creado ficheros SQL reales. Esta seria la propuesta para una carpeta `migrations/`.

### `migrations/001_create_users.sql`

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(40) NOT NULL,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  passwordHash VARCHAR(255) NOT NULL,
  country VARCHAR(255) NULL,
  role ENUM('NORMAL', 'ADMIN') NOT NULL DEFAULT 'NORMAL',
  state ENUM('Activa', 'Inactiva') NOT NULL DEFAULT 'Activa',
  createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  bio TEXT NULL,
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### `migrations/002_create_movies_catalog.sql`

```sql
CREATE TABLE directors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tmdb_id INT NULL,
  name VARCHAR(255) NOT NULL,
  photo_path VARCHAR(255) NULL,
  UNIQUE KEY uq_directors_tmdb_id (tmdb_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tmdb_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  release_date DATE NULL,
  poster_path VARCHAR(255) NULL,
  director_id INT NULL,
  UNIQUE KEY uq_movies_tmdb_id (tmdb_id),
  KEY idx_movies_director_id (director_id),
  CONSTRAINT fk_movies_director
    FOREIGN KEY (director_id) REFERENCES directors(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### `migrations/003_create_lists.sql`

```sql
CREATE TABLE lists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  visibility ENUM('Publica', 'Privada') NOT NULL DEFAULT 'Privada',
  createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  UNIQUE KEY uq_lists_user_title (user_id, title),
  CONSTRAINT fk_lists_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE list_movies (
  list_id INT NOT NULL,
  movie_id INT NOT NULL,
  PRIMARY KEY (list_id, movie_id),
  KEY idx_list_movies_movie_id (movie_id),
  CONSTRAINT fk_list_movies_list
    FOREIGN KEY (list_id) REFERENCES lists(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_list_movies_movie
    FOREIGN KEY (movie_id) REFERENCES movies(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### `migrations/004_create_reviews.sql`

```sql
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  movie_id INT NOT NULL,
  rating DECIMAL(3,1) NOT NULL,
  title VARCHAR(50) NULL,
  comment TEXT NULL,
  visibility ENUM('Publica', 'Privada') NOT NULL DEFAULT 'Privada',
  spoiler TINYINT(1) NOT NULL DEFAULT 0,
  createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_reviews_user_movie (user_id, movie_id),
  KEY idx_reviews_movie_id (movie_id),
  CONSTRAINT fk_reviews_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_reviews_movie
    FOREIGN KEY (movie_id) REFERENCES movies(id)
    ON DELETE CASCADE,
  CONSTRAINT chk_reviews_rating
    CHECK (rating >= 0 AND rating <= 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### `migrations/005_create_providers.sql`

```sql
CREATE TABLE providers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tmdb_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  logo_path VARCHAR(255) NULL,
  country VARCHAR(255) NULL,
  UNIQUE KEY uq_providers_tmdb_id (tmdb_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE movie_providers (
  movie_id INT NOT NULL,
  provider_id INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  country VARCHAR(255) NULL,
  PRIMARY KEY (movie_id, provider_id, type),
  KEY idx_movie_providers_provider_id (provider_id),
  CONSTRAINT fk_movie_providers_movie
    FOREIGN KEY (movie_id) REFERENCES movies(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_movie_providers_provider
    FOREIGN KEY (provider_id) REFERENCES providers(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### `migrations/006_seed_demo_data.sql`

```sql
INSERT INTO users (name, username, email, passwordHash, role, state)
VALUES
  ('Demo User', 'demo_user', 'demo@example.test', '$2y$10$replace_with_real_hash', 'NORMAL', 'Activa');
```

Notas:

- El hash debe generarse con `password_hash`.
- No usar emails reales.
- No incluir tokens ni contrasenas planas.

## Apoyo didactico: migraciones en PHP

### Que es una migracion

Una migracion es un cambio versionado de la base de datos. No es simplemente "tener un SQL". La diferencia importante es esta:

- Un dump SQL describe un estado completo en un momento.
- Una migracion describe un paso concreto de cambio: crear tabla, anadir columna, crear indice, corregir constraint, insertar seed minimo, etc.
- Un sistema de migraciones sabe que pasos ya se ejecutaron y cuales faltan.

En Eurofilm ahora existe `src/databases/eurofilm_db.sql`, que es un dump de phpMyAdmin. Sirve para reconstruir una base en local, pero no explica la historia de cambios ni permite evolucionar de forma ordenada. Por eso el patron recomendado es pasar a una carpeta `migrations/`.

### Migraciones manuales

Las migraciones manuales son ficheros SQL escritos por el estudiante. No requieren dependencias nuevas. Para Eurofilm, seria una opcion razonable si el objetivo academico es aprender SQL y mantener control del esquema.

Ejemplo de estructura:

```text
migrations/
  001_create_users.sql
  002_create_movies.sql
  003_create_lists.sql
  004_create_reviews.sql
  005_create_providers.sql
  006_seed_demo_data.sql
```

Ventajas:

- Son faciles de leer por profesorado.
- No obligan a instalar mas paquetes.
- Encajan bien con XAMPP, MariaDB y phpMyAdmin.
- Ayudan a demostrar conocimiento real de SQL.

Inconvenientes:

- El orden y ejecucion dependen de disciplina.
- No hay control automatico de que migraciones ya se ejecutaron, salvo que se cree una tabla propia.
- Los rollback deben escribirse aparte.
- Es facil olvidar una migracion si se cambia la base a mano desde phpMyAdmin.

Un sistema manual minimo podria tener una tabla:

```sql
CREATE TABLE schema_migrations (
  version VARCHAR(100) PRIMARY KEY,
  executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

Y cada vez que se ejecute `001_create_users.sql`, se registra:

```sql
INSERT INTO schema_migrations (version) VALUES ('001_create_users');
```

Esto ya permite saber si una base esta al dia. No automatiza todo, pero introduce el patron correcto.

### Migraciones manuales con script propio

Una mejora intermedia es crear un script PHP de consola, por ejemplo `tools/migrate.php`, que:

1. Lea la carpeta `migrations/`.
2. Ordene los ficheros por nombre.
3. Consulte `schema_migrations`.
4. Ejecute solo los SQL no aplicados.
5. Registre cada migracion ejecutada.

Esto sigue siendo "manual" en cuanto a que los SQL los escribe el estudiante, pero automatiza la ejecucion.

Ejemplo conceptual:

```bash
php tools/migrate.php
```

Y en `composer.json` podria exponerse como script:

```json
{
  "scripts": {
    "migrate": "php tools/migrate.php"
  }
}
```

Entonces se podria ejecutar:

```bash
composer migrate
```

Composer documenta que los scripts permiten asociar comandos reutilizables al proyecto. Esto no convierte Composer en una herramienta de migraciones por si mismo; Composer solo ejecuta el comando definido.

### Migraciones con dependencias de Composer

Una dependencia de Composer aporta una herramienta ya hecha para crear, ejecutar, revertir y consultar migraciones. Las mas relevantes para este proyecto son:

| Herramienta | Tipo | Encaje con Eurofilm |
|---|---|---|
| Phinx | Independiente de framework | Muy buen encaje porque Eurofilm no usa Laravel ni Symfony |
| Doctrine Migrations | Libreria PHP madura | Buena opcion, especialmente si mas adelante se usa Doctrine DBAL/ORM |
| Laravel Migrations | Integrada en Laravel | No encaja directamente salvo que el proyecto migre a Laravel |

### Opcion recomendada para Eurofilm: Phinx

Phinx esta pensada para migraciones en PHP sin depender de un framework completo. Su documentacion indica instalacion con Composer y uso desde `vendor/bin/phinx`.

Instalacion conceptual:

```bash
composer require --dev robmorgan/phinx
```

Inicializacion conceptual:

```bash
vendor/bin/phinx init
```

Creacion de migracion:

```bash
vendor/bin/phinx create CreateUsersTable
```

Ejecucion:

```bash
vendor/bin/phinx migrate
```

Rollback:

```bash
vendor/bin/phinx rollback
```

Relacion con Eurofilm:

- Phinx leeria credenciales de entorno o de un fichero `phinx.php`.
- Las migraciones vivirian en `db/migrations` o `migrations`.
- El proyecto dejaria de depender de importar manualmente `src/databases/eurofilm_db.sql`.
- En una entrega, el README podria decir: instalar dependencias, crear `.env`, crear base vacia y ejecutar migraciones.

Ejemplo de migracion Phinx:

```php
<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table
            ->addColumn('name', 'string', ['limit' => 40])
            ->addColumn('username', 'string', ['limit' => 50])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('passwordHash', 'string', ['limit' => 255])
            ->addColumn('role', 'string', ['limit' => 30, 'default' => 'NORMAL'])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['username'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
```

Phinx tambien permite usar SQL directo dentro de una migracion si se quiere mantener sintaxis MariaDB concreta.

### Opcion alternativa: Doctrine Migrations

Doctrine Migrations tambien se instala con Composer y ejecuta migraciones desde `vendor/bin/doctrine-migrations`. Su documentacion distingue entre:

- `generate`: crea una clase de migracion vacia.
- `diff`: genera una migracion comparando el esquema actual con metadatos/mapping.
- `migrate`: aplica migraciones pendientes.
- `execute --up` y `execute --down`: ejecutan una migracion concreta hacia arriba o hacia abajo.

La parte de "generar automaticamente" suele depender de tener un modelo de esquema formal: entidades Doctrine, mapping XML/YAML/PHP o configuracion de DBAL. En Eurofilm, como los modelos actuales son clases PDO manuales y no entidades ORM, Doctrine no podria inferir todo el esquema automaticamente sin trabajo adicional.

Por eso, para Eurofilm:

- Doctrine Migrations seria valido si se quiere una herramienta robusta.
- Phinx es mas simple para un proyecto PHP propio sin framework.
- SQL manual es suficiente si se documenta bien y se ejecuta con disciplina.

### Laravel Migrations como comparacion

Laravel tiene migraciones integradas con `php artisan make:migration` y `php artisan migrate`. Esto automatiza mucho porque Laravel ya trae:

- estructura de proyecto
- configuracion de base de datos
- tabla `migrations`
- comandos de consola
- Schema Builder

Pero Eurofilm no es Laravel. Instalar Laravel solo para migraciones no tiene sentido. Si se quisiera migrar todo el proyecto a Laravel, entonces si tendria sentido usar sus migraciones, controladores, rutas, middleware de sesion y vistas Blade.

### Automatiza las migraciones?

Depende de que se entienda por automatizar:

- SQL manual: no automatiza la creacion ni el tracking salvo que se cree un script.
- Script propio: automatiza la ejecucion y registro, pero no escribe los SQL.
- Phinx: automatiza creacion de esqueletos, tracking, ejecucion y rollback. El estudiante escribe la logica.
- Doctrine Migrations: automatiza tracking y ejecucion; puede generar diffs si existe metadata de esquema.
- Laravel: automatiza el flujo completo dentro del framework.

Una herramienta seria no deberia modificar la base "magicamente" sin revision. Lo normal es generar una migracion, revisarla y ejecutarla.

### Como se relaciona con el proyecto web

Las migraciones no se ejecutan en cada visita del usuario. No pertenecen a `public/`. Son una tarea de instalacion, despliegue o mantenimiento.

Flujo correcto para Eurofilm:

1. Clonar proyecto.
2. Ejecutar `composer install`.
3. Copiar `.env.example` a `.env`.
4. Crear base de datos vacia `eurofilm_db`.
5. Ejecutar migraciones.
6. Arrancar Apache/XAMPP.
7. Abrir la aplicacion.

En produccion o entrega, las migraciones se ejecutan antes de usar la app. Los controladores (`AuthController`, `MovieController`, `ApiMovieController`) asumen que las tablas ya existen.

## Apoyo didactico: paginas publicas PHP frente a HTML

### Situacion actual

En `public/` hay muchas paginas con extension `.php`, por ejemplo:

- `public/movies/movies.php`
- `public/movies/card.php`
- `public/series/series.php`
- `public/series/card.php`
- `public/pantalla/perfil.php`
- `public/pantalla/login.php`
- `public/pantalla/register.php`

La razon historica es comprensible: se uso PHP para incluir cabecera, footer, barra de busqueda, filtros y otros fragmentos.

Ejemplo de patron actual:

```php
<?php include '../inc/header.php'; ?>
<?php include '../busqueda.php'; ?>
<?php include 'filtros.php'; ?>
```

Esto funciona, pero mezcla dos enfoques:

1. Paginas PHP renderizadas por servidor.
2. Frontend que consume una API mediante JavaScript.

### Es obligatorio que solo exista `index.php`?

No es una verdad universal de PHP. En PHP pueden existir muchas paginas `.php`, y eso es valido. Tambien puede existir un unico `index.php` como front controller. Ambas opciones existen.

Lo que probablemente quiere decir el tutor es mas concreto:

- Si el proyecto se presenta como API + frontend, entonces las paginas de interfaz deberian ser HTML/CSS/JS estaticos.
- PHP deberia quedarse como backend: `public/index.php` recibe rutas y llama a controladores.
- Las vistas no deberian estar repartidas como archivos PHP con includes si no se esta usando un patron MVC con vistas PHP.

Por tanto: "debe existir solo `index.php` PHP" no es una ley tecnica, pero si puede ser una decision arquitectonica coherente para este proyecto.

### Dos arquitecturas validas

#### Opcion A: MVC clasico con vistas PHP

En esta opcion, si tiene sentido que existan vistas PHP. La estructura seria algo asi:

```text
public/
  index.php
src/
  controllers/
  models/
  views/
    layout/
      header.php
      footer.php
    movies/
      index.php
      show.php
```

El usuario no abre `public/movies/movies.php`. Abre una ruta como:

```text
/Eurofilm/movie/index
```

El controlador carga datos y renderiza una vista PHP.

Ventaja:

- PHP puede manejar sesion, permisos, cabecera y render inicial.

Inconveniente:

- Si ya se consume una API con JS, se duplica trabajo.
- Hace falta ordenar de verdad el MVC.

#### Opcion B: API PHP + frontend estatico HTML/CSS/JS

En esta opcion, que parece la recomendada por el tutor, `index.php` queda como entrada del backend y las paginas son HTML:

```text
public/
  index.php
  pages/
    movies.html
    movie-detail.html
    series.html
    login.html
    register.html
    profile.html
  components/
    header.html
    search.html
    filters.html
  css/
  js/
src/
  controllers/
  models/
  services/
```

El HTML no ejecuta PHP. La sesion se consulta llamando a la API.

Ejemplo:

```js
const response = await fetch('/Eurofilm/auth/usuario', {
  credentials: 'include'
});

const usuario = await response.json();
```

MDN documenta que `fetch` puede enviar credenciales como cookies usando la opcion `credentials`. En mismo origen normalmente se usaria `same-origin`; en este proyecto ya se usa `include` en varios puntos.

### Entonces, como funciona la sesion PHP si la pagina es HTML?

La sesion PHP no necesita que el documento HTML sea PHP. Necesita que las peticiones a endpoints PHP envien la cookie de sesion.

Flujo:

1. `login.html` envia usuario/contrasena a `/Eurofilm/auth/login`.
2. `AuthController::login` valida credenciales.
3. PHP crea o actualiza `$_SESSION`.
4. El navegador conserva la cookie de sesion PHP.
5. `profile.html` llama a `/Eurofilm/auth/perfil` con `fetch(..., { credentials: 'include' })`.
6. PHP lee la cookie, recupera la sesion y responde JSON.
7. JS pinta el perfil en el HTML.

La pagina HTML no ve `$_SESSION`. Solo ve la respuesta JSON de la API.

Esto tambien vale si mas adelante se usan cookies propias:

- La cookie la crea el backend con `Set-Cookie`.
- El navegador la guarda.
- `fetch` la envia si la politica de `credentials`, dominio, path, SameSite y Secure lo permiten.
- El backend la valida.

### Como se incluiria cabecera, busqueda y filtros sin PHP?

Hay varias opciones.

#### Opcion 1: componentes cargados con `fetch`

Crear fragmentos HTML:

```text
public/components/header.html
public/components/search.html
public/components/filters.html
```

Y cargarlos desde JS:

```js
async function loadComponent(selector, url) {
  const target = document.querySelector(selector);
  const response = await fetch(url);
  target.innerHTML = await response.text();
}

await loadComponent('#site-header', '/Eurofilm/public/components/header.html');
await loadComponent('#search-bar', '/Eurofilm/public/components/search.html');
```

Advertencia: si se usa `innerHTML` con HTML propio controlado por el proyecto es aceptable con cuidado, pero no debe usarse para datos de usuarios, reviews o API externa.

#### Opcion 2: Web Components nativos

Crear un componente JS:

```js
class AppHeader extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
      <header>
        <a href="/Eurofilm/public/pages/movies.html">Eurofilm</a>
        <nav id="session-nav"></nav>
      </header>
    `;
  }
}

customElements.define('app-header', AppHeader);
```

Y usar:

```html
<app-header></app-header>
```

Ventaja:

- No requiere PHP.
- No requiere framework.
- Encaja bien con HTML/JS/CSS.

#### Opcion 3: build tool o generador estatico

Usar Vite, npm scripts o un generador que permita componentes. Para este proyecto puede ser excesivo si el objetivo es DAW/PHP, pero seria una opcion profesional.

#### Opcion 4: Apache Server Side Includes

Apache permite Server Side Includes para insertar fragmentos en HTML procesado por el servidor. La documentacion de Apache explica `mod_include` y SSI.

Ejemplo conceptual:

```html
<!--#include virtual="/Eurofilm/public/components/header.html" -->
```

Inconveniente:

- Requiere configuracion de Apache.
- Suele usar extension `.shtml` o configurar Apache para procesar `.html`.
- No es tan habitual en proyectos modernos como JS components o un framework.

Para Eurofilm no seria mi primera recomendacion.

### Que mejoraria Eurofilm con frontend HTML + API

Beneficios:

- Separacion mas clara: PHP = API/backend, HTML/JS/CSS = interfaz.
- Menos includes PHP repartidos.
- Menos riesgo de mezclar sesion/render/HTML en ficheros sueltos.
- Mas facil explicar al profesorado: "el frontend consume los controladores API".
- Mejor camino hacia SPA ligera sin framework.

Riesgos:

- Hay que rehacer cabecera, busqueda y filtros como componentes JS.
- Hay que proteger rutas privadas desde JS y desde backend.
- La seguridad no desaparece: CSRF, XSS, sesiones y cookies siguen siendo responsabilidad del backend/frontend.
- Si alguien abre `profile.html` sin login, el HTML cargara, pero JS debe llamar a `/auth/perfil` y redirigir o mostrar acceso denegado.

### Patron recomendado para Eurofilm

La recomendacion equilibrada para este proyecto seria:

1. Mantener `public/index.php` como front controller de backend.
2. Convertir paginas publicas de interfaz a `.html` progresivamente.
3. Crear `public/components/` para cabecera, busqueda y filtros.
4. Crear un modulo JS `session.js`:

```js
export async function getCurrentUser() {
  const response = await fetch('/Eurofilm/auth/usuario', {
    credentials: 'include'
  });

  if (!response.ok) {
    return null;
  }

  return response.json();
}
```

5. Crear un modulo JS `layout.js` que cargue componentes y pinte el estado de sesion.
6. Mantener todos los permisos en backend. El frontend solo mejora experiencia, no autoriza.
7. Documentar en README que el backend es PHP y el frontend consume endpoints JSON.

### Errores relacionados detectados en Eurofilm

1. `public/inc/header.php` ejecuta `session_start()` y pinta HTML segun `$_SESSION`. Si se pasa a HTML, esto debe moverse a JS consumiendo `/auth/usuario`.
2. `public/movies/card.php` lee `$_SESSION['usuario']['id']` directamente. En HTML deberia obtenerse desde API.
3. `public/busqueda.php`, `public/movies/filtros.php` y similares son componentes de interfaz, no deberian necesitar PHP si no contienen logica de servidor.
4. Si solo queda `index.php`, las rutas a paginas deberian actualizarse, por ejemplo de `/public/movies/card.php?id=...` a `/public/pages/movie-detail.html?id=...`.
5. El backend debe seguir siendo responsable de sesion, CSRF y validacion.

### Decision final recomendada

Para defender el proyecto ante profesorado, se puede explicar asi:

> Eurofilm esta evolucionando desde paginas PHP con includes hacia una arquitectura API + frontend estatico. Por eso se mantiene `public/index.php` como entrada PHP para controladores, y las pantallas de usuario pasan a HTML/CSS/JS. La sesion no se renderiza en HTML mediante PHP; se consulta mediante endpoints JSON protegidos por la cookie de sesion. Los componentes comunes como cabecera, busqueda y filtros se cargan con JavaScript o se implementan como Web Components.

Esta explicacion es tecnicamente correcta y responde al reto de sesiones/cookies sin obligar a mantener vistas PHP.

## Plan de mejora recomendado para el estudiante

### Fase 1: dejar el proyecto entregable

1. Quitar `.env` del repositorio y crear `.env.example`.
2. Regenerar token/API key de TMDB.
3. Eliminar `readme-falso.md`.
4. Escribir README real de instalacion.
5. Eliminar `var_dump($_POST)`.
6. Corregir bug de favoritos duplicado.
7. Corregir `btn` no definido en `favorites.js`.
8. Escapar salida PHP visible.
9. Cambiar renderizado JS de reviews/favoritos a `textContent`.
10. Crear carpeta `migrations/` con SQL versionado.

### Fase 2: mejorar seguridad

1. Anadir CSRF token a formularios y `fetch`.
2. Regenerar sesion tras login y registro.
3. Normalizar uso de `$_SESSION['usuario']`.
4. Validar todos los campos del servidor.
5. Activar verificacion SSL en cURL.
6. Devolver errores JSON consistentes.

### Fase 3: mejorar arquitectura

1. Unificar namespaces `Core`/`core`.
2. Decidir si el proyecto sera MVC completo o paginas PHP con endpoints API.
3. Crear whitelist de rutas/metodos.
4. Mover logica de sesion/autorizacion a una capa comun.
5. Separar vistas de datos.
6. Anadir tests minimos de modelos/controladores.

## Calificacion de patrones

Escala:

- Correcto: buen patron, mantener.
- Aceptable: funciona, pero necesita orden.
- Deficiente: causa deuda tecnica o bugs.
- Critico: riesgo de seguridad, entrega o perdida de datos.

| Patron | Calificacion | Motivo |
|---|---:|---|
| Composer + PSR-4 | Aceptable | Buena base, namespaces inconsistentes |
| PDO con prepared statements | Correcto | Buen patron de acceso a datos |
| Hash de contrasenas | Correcto | Usa `password_hash` y `password_verify` |
| MVC parcial | Aceptable | Hay capas, pero se mezclan con paginas directas |
| Gestion de secretos | Critico | `.env` contiene credenciales y token real |
| CSRF | Critico | No hay proteccion en acciones con efectos |
| XSS / salida HTML | Critico | `innerHTML` y `echo` sin escape suficiente |
| Sesiones | Deficiente | Claves inconsistentes y sin regeneracion tras login |
| Migraciones | Deficiente | Solo hay dump phpMyAdmin con datos |
| Esquema SQL | Aceptable | Buen inicio con FK, pero faltan constraints y hay duplicados |
| Validacion backend | Deficiente | Tipos/rangos/campos incompletos |
| Servicio TMDB | Deficiente | SSL desactivado y sin manejo robusto de errores |
| README/documentacion | Deficiente | Falta guia real de ejecucion |
| Calidad de entrega | Deficiente | Archivo ofensivo y datos personales de prueba |

## Conclusion

El proyecto es recuperable y tiene una base suficiente para una entrega academica si se corrigen los riesgos principales. Lo mas importante para ayudar al estudiante no es reescribir todo, sino cerrar los huecos que un evaluador o un despliegue real detectarian rapido: secretos, CSRF, XSS, migraciones, README y bugs funcionales.

La mejora con mayor retorno seria esta secuencia:

1. Seguridad basica: secretos, CSRF, XSS, sesiones.
2. Bugs funcionales: favoritos, reviews, perfil, mailer.
3. Base de datos: migraciones limpias y seeds ficticios.
4. Presentacion final: README profesional y eliminacion de archivos no entregables.

Con esos cambios, Eurofilm pasaria de "prototipo funcional con riesgos" a "proyecto web academico defendible".
