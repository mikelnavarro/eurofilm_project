import { BASE_URL } from "./configuracion.js";
// Referencias
const registerForm = document.getElementById("form-register");
if (registerForm) {
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(registerForm);
    try {
      const res = await fetch(`/Eurofilm/auth/registrarse`, {
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
          window.location.href = "./movies.php";
        }, 800);
      } else {
        showMessage(data.error, true);
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
