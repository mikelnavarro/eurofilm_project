const searchInput = document.getElementById("search-user");
const countryFilter = document.getElementById("filter-country");
const container = document.getElementById("users-container");
async function loadUsers() {

    const q = searchInput.value;
    const country = countryFilter.value;

    const res = await fetch(
        `/Eurofilm/auth/searchUsers?q=${encodeURIComponent(q)}&country=${encodeURIComponent(country)}`
    );

    const data = await res.json();

    container.innerHTML = "";

    if (!data.users.length) {
        container.innerHTML = "<p>No hay usuarios</p>";
        return;
    }

    data.users.forEach(user => {

        container.innerHTML += `
            <div class="user-card">

                <h3>${user.username}</h3>

                <p>${user.country ?? 'Sin país'}</p>

                <small>${user.email}</small>

            </div>
        `;
    });
}
searchInput.addEventListener("input", loadUsers);
countryFilter.addEventListener("change", loadUsers);
loadUsers();