Métodos usados

Para la ApiController.php, devolviendo detalles de un filme.
```php
// DETALLES PRINCIPALES
$data = $this->tmdb->consultar("/movie/$id");
// CRÉDITOS
$credits = $this->tmdb->consultar("/movie/$id/credits");
$data['crew'] = $credits['crew'];
$data['cast'] = array_slice($credits['cast'], 0, 5);

// TRAILER
$videos = $this->tmdb->consultar("/movie/$id/videos");

$trailer = null;
if (!empty($videos['results'])) {
foreach ($videos['results'] as $video) {
if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
$trailer = $video['key'];
break;
}
}
}
$data["trailer"] = $trailer;
// PROVEEDORES
$watch_providers = $this->tmdb->consultar("/movie/$id/watch/providers");
$data['watch_providers'] = $watch_providers;

```
**Para ApiController, para la serie individual
```php
        // DETALLES PRINCIPALES
        $data = $this->tmdb->consultar("/tv/$id");
        // CRÉDITOS
        $credits = $this->tmdb->consultar("/tv/$id/credits");
        $data['cast'] = array_slice($credits['cast'], 0, 5);



        // TRAILER
        $videos = $this->tmdb->consultar("/tv/$id/videos");
        $trailer = null;
        if (!empty($videos['results'])) {
            foreach ($videos['results'] as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                    $trailer = $video['key'];
                    break;
                }
            }
        }

        $data["trailer"] = $trailer;
        // PROVEEDORES
        $watch_providers = $this->tmdb->consultar("/tv/$id/watch/providers");
        $data['watch_providers'] = $watch_providers;
```