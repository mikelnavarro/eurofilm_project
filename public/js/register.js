import { BASE_URL } from "./configuracion.js";
// Referencias
const registerForm = document.getElementById("form-register");
if (registerForm) {
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const response = await registrarse(registerForm);

    if (response.success) {
      showMessage("Usuario registrado correctamente");

      setTimeout(() => {
        window.location.href = "./login.php";
      }, 600);
    } else {
      console.log(response.error);
    }
  });
}
// metodos
async function registrarse(form) {
  try {
    const formData = new FormData(form);

    const res = await fetch(`Eurofilm/index.php?url=auth/registrarse`, {
      method: "POST",

      body: formData,

      credentials: "include",
    });
    const data = await res.json();

    console.log(data);

    if (!res.ok) {
      return { success: false, error: data.error };
    }

    return { success: true, data };
  } catch (error) {
    console.error("Error register:", error);

    return { success: false };
  }
}
// Función obtener datos de form
function getFormData() {
  const nombre = document.getElementById("nombre").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  return { email, password };
}

// funcion mostrar mensaje
function showMessage(text, isError = false) {
  const msg = document.getElementById("mensaje");

  msg.textContent = text;
  msg.style.color = isError ? "red" : "green";
}
