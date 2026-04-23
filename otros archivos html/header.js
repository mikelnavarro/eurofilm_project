import { RUTA_URL, RUTA_PATH, NOMBRESITIO } from 'config.php';
export function renderHeader() {
  const headerHTML = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="${RUTA_PATH}/favicon.ico" type="image/x-icon">
</head>
    <header style="background: #141414; padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e50914;">
        <h1 style="color: #e50914; margin: 0;"> Eurofilm</h1>
        <nav>
            <a href="index.html" style="color: white; margin-right: 15px; text-decoration: none;">Inicio</a>
            <a href="favoritos.html" style="color: white; text-decoration: none;">Mis Favoritos</a>
        </nav>
    </header>
    `;
  document.body.insertAdjacentHTML("afterbegin", headerHTML);
}
