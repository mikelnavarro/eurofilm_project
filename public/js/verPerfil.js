import { BASE_URL } from "./configuracion.js";
async function cargarPerfil() {
  const res = await fetch("/Eurofilm/auth/perfil", {
    credentials: "include",
  });

  const data = await res.json();

  if (!data.ok) return;

  const u = data.usuario;
  document.getElementById("username").value = u.username ?? "";
  document.getElementById("email").textContent = u.email;
  document.getElementById("country").value = u.country ?? "";
}
