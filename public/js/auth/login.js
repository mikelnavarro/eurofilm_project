import { PUBLIC_URL, BASE_URL } from "../configuracion.js";

// Función importada
// Referencias
const loginForm = document.getElementById("loginForm");
// metodos

if (loginForm) {
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);
    try {
      const res = await fetch(`${BASE_URL}/auth/login`, {
        method: "POST",
        body: formData,
        credentials: "include",
      });
      const data = await res.json();
      if (data.ok) {
        showMessage("Usuario registrado correctamente");
        window.location.replace(`${PUBLIC_URL}/movies/movies.html`);
        setTimeout(() => {
          window.location.replace(`${PUBLIC_URL}/movies/movies.html`);
        }, 800);
      } else {
        showMessage("E-mail o clave incorrectos. Inténtelo otra vez", true);
      }
    } catch (error) {
      console.error(error);
      showMessage("Error de servidor", true);
    }
  });
}

// funcion mostrar mensaje
function showMessage(text, isError = false) {
  const msg = document.getElementById("mensaje");

  msg.textContent = text;
  msg.style.color = isError ? "red" : "green";
}
