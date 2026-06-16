import { BASE_URL } from "../configuracion.js";
// Referencias
const registerForm = document.getElementById("form-register");
if (registerForm) {
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(registerForm);
    try {
      const res = await fetch(`${BASE_URL}/auth/registrarse`, {
        method: "POST",
        body: formData,
        credentials: "include",
      });

      const data = await res.json();
      const text = await res.text();

      console.log(text);
      if (data.ok) {
        showMessage("Usuario registrado correctamente");

        setTimeout(() => {
          window.location.reload();
          window.location.replace(`${PUBLIC_URL}/movies/movies.html`);
        }, 800);
      } else {
        showMessage(data.error, true);
      }
      // register con response ok
      if (res.ok) {
        console.log("¡Éxito! Recargando...");
        window.location.reload();
      } else {
        alert("Algo salió mal: " + data.message);
      }
    } catch (error) {
      console.error(error);
      showMessage("Error de servidor", true);
    }
  });
}
// metodos

// funcion mostrar mensaje
function showMessage(text, isError = false) {
  const msg = document.getElementById("mensaje");

  msg.textContent = text;
  msg.style.color = isError ? "red" : "green";
}
