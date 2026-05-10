
// cambiar Visibilidad
document.getElementById("visibility-fav").addEventListener("change", async (e) => {
  const formData = new FormData();
  formData.append("visibility", e.target.value);

  const res = await fetch("/Eurofilm/movies/changeFavoritesVisibility", {
    method: "POST",
    body: formData,
    credentials: "include"
  });

  const data = await res.json();

  if (!data.ok) {
    alert("Error al cambiar visibilidad");
  }
});