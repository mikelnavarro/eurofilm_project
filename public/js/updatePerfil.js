const boton = document.getElementById("btn-edit");

boton.addEventListener("click", () => {
  // Selecciona todos los input del formulario
  const inputs = document.querySelectorAll("form input, form textarea");
  inputs.forEach((input) => {
    input.disabled = false;
  });
});
const form = document.getElementById("form-profile");
const saveBtn = document.getElementById("btn-save");

// valores originales
const originalData = {
  nombre: document.getElementById("nombre").value,
  username: document.getElementById("username").value,
  email: document.getElementById("email").value,
  country: document.getElementById("country").value,
  bio: document.getElementById("bio").value
};


form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);


    const name = formData.get("nombre");
    const username = formData.get("username");
    const email = formData.get("email");
    const country = formData.get("country");
    const bio = formData.get("bio");
    // si no cambió nada, no envía
    if (
      username === originalData.username &&
      email === originalData.email &&
      country === originalData.country &&
      bio === originalData.bio
    ) {
      alert("No hay cambios");
      return;
    }

    const res = await fetch("/Eurofilm/auth/updateProfile", {
      method: "POST",
      body: formData,
      credentials: "include",
    });

    const data = await res.json();
    if (data.error) {
      alert(data.error);
      return;
    }

    if (data.ok) {
      alert("Perfil actualizado");
    }
  });



// Habilitar botón si hay cambios
function checkChanges() {
  const changed =
    username.value !== originalData.username ||
    email.value !== originalData.email ||
    country.value !== originalData.country;
    bio.value !== originalData.bio;

  saveBtn.disabled = !changed;
}

// escuchar cambios
username.addEventListener("input", checkChanges);
email.addEventListener("input", checkChanges);
country.addEventListener("input", checkChanges);