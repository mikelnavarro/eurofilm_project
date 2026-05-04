import { BASE_URL } from "./configuracion.js";
export async function apiGet(endpoint, params = {}) {
  const url = new URL(BASE_URL + endpoint, window.location.origin);

  
  Object.keys(params).forEach((key) =>
    url.searchParams.append(key, params[key]),
  );

  const res = await fetch(url);
  const data = await res.json();

  return data.results;
}
