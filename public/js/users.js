const searchInput = document.getElementById("search-user");
const countryFilter = document.getElementById("filter-country");
const container = document.getElementById("users-container");
async function loadUsers() {
  const q = searchInput.value;
  const country = countryFilter.value;

  const res = await fetch(
    `/Eurofilm/auth/searchUsers?q=${encodeURIComponent(q)}&country=${encodeURIComponent(country)}`,
  );

  const data = await res.json();
 
  if (data.ok) {
    renderizar(container,data.users);
  }
  console.log(data.users);
}
searchInput.addEventListener("input", loadUsers);
countryFilter.addEventListener("change", loadUsers);

function renderizar(container, users) {
  container.innerHTML = "";
  if (!users.length) {
    container.innerHTML = "<p>No hay usuarios</p>";
    return;
  }
  if (!Array.isArray(users)) return;
  users.forEach((user) => {
    const card = document.createElement("div");
    card.className = "user-card";

    const username = document.createElement("h3");
    username.textContent = user.username;

    const country = document.createElement("p");
    country.textContent = user.country ?? "Sin país";

    const bio = document.createElement("p");
    bio.textContent = user.bio ?? "Sin biografía";

    const email = document.createElement("small");
    email.textContent = user.email;

    const date = document.createElement("small");
    const formattedDate = user.createdAt
      ? new Date(user.createdAt).toLocaleString("es-ES")
      : "Sin fecha";

    date.textContent = formattedDate;

    // estructura
    card.append(username, country, bio, email, date);
    container.appendChild(card);
  });
}
loadUsers();
