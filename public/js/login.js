import { BASE_URL } from "./configuracion.js";
// Referencias
const formLogin = document.getElementById("form-login");
const loginMsg = document.getElementById("login-msg");

function getFormData() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    return { email, password };
}
// metodos
async function login() {
  try {
    const res = await fetch(`Eurofilm/api/auth/login`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: email,
        password: password,
      }),
    });

    if (!res.ok) {
      throw new Error(`Error HTTP: ${res.status}`);
    }
    const data = await res.json();
    console.log("Respuesta login:", data);
    return data.results;
  } catch (error) {
    console.error("Error en fetchMovies:", error);
    return null;
  }
}

// login
// ----------------------
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");

formLogin.addEventListener("submit", async (e) => {
    e.preventDefault();

    const data = getFormData();

    if (!data.email || !data.password) {
      showMessage("Rellena todos los campos", true);
      return;
    }
    const response = await login(data);
    if (response && response.success) {
      showMessage("Login correcto");
      // Ejemplo: redirección
    } else {
      showMessage("Credenciales incorrectas", true);
    }

  if (!res.ok) {
    loginMsg.textContent = data.error || "Error al iniciar sesión";
    return;
  }

  loginMsg.textContent = "Login correcto ✔";

  // redirigir o actualizar UI
  window.location.href = "/Eurofilm/public/movies/movies.php";
  });
});

// funcion mostrar mensaje
function showMessage(text, isError = false) {
  const msg = document.getElementById("mensaje");

  msg.textContent = text;
  msg.style.color = isError ? "red" : "green";
}
